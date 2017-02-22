var LangJS = LangJS || {};

LangJS.Loader = {

};

LangJS.Translator = {
    parsed:{},
    loader:null,
    locale:null,
    fallback:null,
    loaded:[],
    selector:null,
    Construct: function(loader, locale){
        this.loader = loader;
        this.locale = locale;
    },
    HasForLocale: function(key, locale){
        locale = locale || null;
        return this.Has(key, locale, false);
    },
    Has: function(key, locale, fallback){
        locale = locale || null;
        fallback = fallback || true;
        return this.get(key, locale, fallback) !== key;
    },
    Trans: function(key, replace, locale){
        replace = replace || [];
        locale = locale || null;
        return this.Get(key,replace,locale);
    },
    Get:function(key, replace, locale, fallback){
        fallback = fallback || true;
        var parsed = this.ParseKey(key);
        namespace=parsed[0];
        group = parsed[1];
        item = parsed[2];

        locales = fallback ? this.LocaleArray(locale)
            : [locale ? locale : this.locale];

        for (i=0; i<locales.length; i++) {
            locale = locales[i];
            if ((line = this.GetLine(
                        namespace, group, locale, item, replace
                    ) != null)) {
                break;
            }
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        if (line!="" && line!=null) {
            return line;
        }

        return key;
    },
    GetFromJson:function(key, replace, locale){
        locale = locale || this.locale;

        this.Load('*','*',locale);

        line = this.KeyIsSet(this.loaded, '*','*',locale,key) ? this.loaded['*']['*'][locale][key] : null;

        if(line=="" || line == null){
            fallback = this.Get(key,replace,locale);

            if(fallback!==key){
                return fallback;
            }
        }
        return this.MakeReplacements(line ? line : key, replace)
    },
    TransChoice:function(key, number, replace, locale){
        replace = replace || [];
        locale = locale || null;
        return this.Choice(key, number, replace, locale);
    },
    Choice: function(key, number, replace, locale){
        replace = replace || [];
        locale = locale || null;

        line = this.Get(key, replace, this.LocaleForChoice(locale));

        if(Array.isArray(number)){
            number = number.length;
        }

        replace['count'] = number;

        return this.MakeReplacements(this.GetSelector.Choose(line, number, locale), replace);
    },
    LocaleForChoice: function(locale){
        return locale ? locale : this.locale ? this.locale : this.fallback;
    },
    GetLine: function(namespace, group, locale, item, replace){
        this.load(namespace, group, locale);
        line = this.KeyIsSet(this.loaded, '*','*',locale,key) ? this.loaded['*']['*'][locale][key] : null;

        if(typeof line == "string"){
            return this.MakeReplacements(line, replace);
        }else if(Array.isArray(line) && line.length > 0){
            return line;
        }
    },
    MakeReplacements: function(line, replace){
        replace = this.SortReplacements(replace) || [];

        if(replace.length > 0){
            for(var key in replace){
                if(replace.hasOwnProperty(key)){
                    value = replace[key];
                    line = line.replace(':' + key, value);
                    line = line.replace(':' + str.toUpperCase(key), str.toUpperCase(value));
                    line = line.replace(':' + this.ucFirst(key), this.ucFirst(value));
                }
            }
        }
        return line;
    },
    SortReplacements: function(replace){
        return replace;  //not we can sort this way, since javascript objects are not ordered  May have to sort at timeof use by sorting an indexed array of the keys
    },
    AddLines: function(lines, locale, namespace){
        namespace = namespace || '*';
        for(var key in lines){
            if(replace.hasOwnProperty(key)) {
                value = lines[key];
                split = key.split('.');
                group = split.splice(0,1);
                item = split.splice(1).join('.');

                this.loaded[namespace] = this.loaded[namespace] || {};
                this.loaded[namespace][group] = this.loaded[namespace][group] || {};
                this.loaded[namespace][group][locale] = this.loaded[namespace][group][locale] || {};
                this.loaded[namespace][group][locale][item] = value;
            }
        }
    },
    Load: function(namespace, group, locale){
        if(this.IsLoaded(namespace, group, locale)){
            return;
        }

        lines = this.loader.Load(locale, group, namespace);
        this.loaded[namespace] = this.loaded[namespace] || {};
        this.loaded[namespace][group] = this.loaded[namespace][group] || {};
        this.loaded[namespace][group][locale] = this.loaded[namespace][group][locale] || lines;
    },
    IsLoaded: function(namespace, group, locale){
        return this.KeyIsSet(this.loaded, namespace, group, locale);
    },
    AddNamespace: function(namespace, hint){
        this.loader.AddNamespace(namespace, hint);
    },
    ParseKey: function(key){
        var segments="";

        if (isDefined(this.parsed[key])) {
            segments = this.parsed[key];
        }

        if (key.indexOf('::') === -1) {
            segments = key.split(".");
            parsed = this.ParseBasicSegments(segments);
        } else {
            parsed = this.ParseNamespacedSegments(key);
        }

        segments = this.parsed[key] = parsed;


        if (!segments[0]) {
            segments[0] = '*';
        }

        return segments;
    },
    LocaleArray: function(locale){
        return (locale ? locale : this.locale).filter(this.fallback);
    },
    ParseBasicSegments: function(segments){
        group = segments[0];

        if(count(segments) == 1){
            return [null, group, null];
        }else{
            item = segments.slice(1).join('.');
            return [null, group, item];
        }
    },
    ParseNamespacedSegments: function(key){
        var split = key.split("::");
        namespace=split[0];
        item = split[1];

        itemSegments = item.split(".");

        groupAndItem = this.ParseBasicSegments(itemSegments).splice(1);

        return namespace.concat(groupAndItem);
    },
    SetParsedKey: function(key, parsed){
        this.parsed[key] = parsed;
    },
    KeyIsSet: function(obj){
        var args = Array.prototype.slice.call(arguments, 1);

        for (var i = 0; i < args.length; i++) {
            if (!obj || !obj.hasOwnProperty(args[i])) {
                return false;
            }
            obj = obj[args[i]];
        }
        return true;
    },
    ucFirst: function(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
};
