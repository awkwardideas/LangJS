<?php return
    <<<SCRIPT
var LangJS = LangJS || {};

LangJS.Selector = {
    Choose: function(line, number, locale){
        segments = line.split('|');
        value = LangJS.Selector.Extract(segments, number);
        
        if (value !== null && value !== undefined) {
            return value.trim();
        }

        segments = LangJS.Selector.StripConditions(segments);

        pluralIndex = LangJS.Selector.GetPluralIndex(locale, number);

        if (segments.length == 1 || segments[pluralIndex] === undefined) {
            return segments[0];
        }

        return segments[pluralIndex];
    },
    Extract: function(segments, number){
        segments.forEach(function(part){
            if(null !== (line = LangJS.Selector.ExtractFromString(part, number))){
                return line;
            }
        });
    },
    ExtractFromString: function(part, number){
        matches = part.match('/^[\{\[]([^\[\]\{\}]*)[\}\]](.*)/s');

        if(matches === null || matches.length != 3){
            return;
        }

        condition = matches[1];

        value = matches[2];

        if(condition.indexOf(',') > -1){
            split = condition.split(',');
            from = condition.shift();
            to = split.join(',');

            if(to == '*' && number <= to){
                return value;
            }else if(from == '*' && number <= to){
                return value;
            }else if(number >= from && number <= to){
                return value;
            }
        }

        return condition == number ? value : null;
    },
    StripConditions: function (segments) {
        return segments.map(function(part){
            return part.replace('/^[\{\[]([^\[\]\{\}]*)[\}\]]/', '');
        });
    },
    GetPluralIndex: function(locale, number){
        switch (locale) {
            case 'az':
            case 'bo':
            case 'dz':
            case 'id':
            case 'ja':
            case 'jv':
            case 'ka':
            case 'km':
            case 'kn':
            case 'ko':
            case 'ms':
            case 'th':
            case 'tr':
            case 'vi':
            case 'zh':
                return 0;
                break;
            case 'af':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                return (number == 1) ? 0 : 1;
            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'hy':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'xbr':
            case 'ti':
            case 'wa':
                return ((number == 0) || (number == 1)) ? 0 : 1;
            case 'be':
            case 'bs':
            case 'hr':
            case 'ru':
            case 'sr':
            case 'uk':
                return ((number % 10 == 1) && (number % 100 != 11)) ? 0 : (((number % 10 >= 2) && (number % 10 <= 4) && ((number % 100 < 10) || (number % 100 >= 20))) ? 1 : 2);
            case 'cs':
            case 'sk':
                return (number == 1) ? 0 : (((number >= 2) && (number <= 4)) ? 1 : 2);
            case 'ga':
                return (number == 1) ? 0 : ((number == 2) ? 1 : 2);
            case 'lt':
                return ((number % 10 == 1) && (number % 100 != 11)) ? 0 : (((number % 10 >= 2) && ((number % 100 < 10) || (number % 100 >= 20))) ? 1 : 2);
            case 'sl':
                return (number % 100 == 1) ? 0 : ((number % 100 == 2) ? 1 : (((number % 100 == 3) || (number % 100 == 4)) ? 2 : 3));
            case 'mk':
                return (number % 10 == 1) ? 0 : 1;
            case 'mt':
                return (number == 1) ? 0 : (((number == 0) || ((number % 100 > 1) && (number % 100 < 11))) ? 1 : (((number % 100 > 10) && (number % 100 < 20)) ? 2 : 3));
            case 'lv':
                return (number == 0) ? 0 : (((number % 10 == 1) && (number % 100 != 11)) ? 1 : 2);
            case 'pl':
                return (number == 1) ? 0 : (((number % 10 >= 2) && (number % 10 <= 4) && ((number % 100 < 12) || (number % 100 > 14))) ? 1 : 2);
            case 'cy':
                return (number == 1) ? 0 : ((number == 2) ? 1 : (((number == 8) || (number == 11)) ? 2 : 3));
            case 'ro':
                return (number == 1) ? 0 : (((number == 0) || ((number % 100 > 0) && (number % 100 < 20))) ? 1 : 2);
            case 'ar':
                return (number == 0) ? 0 : ((number == 1) ? 1 : ((number == 2) ? 2 : (((number % 100 >= 3) && (number % 100 <= 10)) ? 3 : (((number % 100 >= 11) && (number % 100 <= 99)) ? 4 : 5))));
            default:
                return 0;
        }
    }
};   
    
LangJS.Translator = {
    dictionary:%JSON%,
    Get:function(key, replace, locale){
        replace = replace || [];
        locale = locale || "en";
        
        key = this.ObtainKey(key, locale);
        return this.MakeReplacements(key, replace);
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
    ObtainKey: function(key, locale){
        split = key.split('.');
        group = split.splice(0,1);
        item = split;
        if(this.KeyIsSet(this.dictionary, locale, group, item)){
            return this.dictionary[locale][group][item];
        }else{
            return key;
        }
    },
    MakeReplacements: function(line, replace){
        replace = this.SortReplacements(replace) || [];

        if(replace.length > 0){
            for(i=0; i<replace.length; i++){
                if(replace[i].hasOwnProperty("value") && replace[i].hasOwnProperty("key")){
                    value = replace[i].value;
                    key = replace[i].key;
                    line = line.replace(':' + key, value);
                    line = line.replace(':' + key.toUpperCase(), String(value).toUpperCase());
                    line = line.replace(':' + this.ucFirst(key), this.ucFirst(value));
                }
            }
        }
        return line;
    },
    SortReplacements: function(replace){
        if(!Array.isArray(replace)){
            return replace;
        }
            
        return replace.sort(function(a, b){
            if(a.key.length > b.key.length){
                return -1;
            }
            if(a.key.length < b.key.length){
                return 1;
            }
            return 0
        });
    },
    Choice: function(key, number, replace, locale){
        replace = replace || [];
        locale = locale || null;

        line = this.Get(key, replace, this.LocaleForChoice(locale));

        if(Array.isArray(number)){
            number = number.length;
        }

        replace.push({
            key:'count',
            value:number
        });

        return this.MakeReplacements(LangJS.Selector.Choose(line, number, locale), replace);
    },
    Singular: function(key, replace, locale){
        replace = replace || [];
        locale = locale || null;

        line = this.Get(key, replace, this.LocaleForChoice(locale));

        replace.push({
            key:'count',
            value:1
        });

        return this.MakeReplacements(LangJS.Selector.Choose(line, 1, locale), replace);
    },
    LocaleForChoice: function(locale){
        return locale ? locale : this.locale ? this.locale : this.fallback;
    },
    ucFirst: function(string){
        return String(string).charAt(0).toUpperCase() + String(string).slice(1);
    }
};

_lang=function(key, replace, locale) {
    replace = replace || [];
    locale = locale || "en";
    return LangJS.Translator.Singular(key,replace,locale);
}
_choice=function(key, number, replace, locale){
    replace = replace || [];
    locale = locale || "en";
    number = number || 0;
    return LangJS.Translator.Choice(key,number,replace,locale);
}
SCRIPT;
