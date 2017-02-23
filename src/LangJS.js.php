<?php return
    <<<SCRIPT
    var LangJS = {
    dictionary:%JSON%,
    Get:function(key, replace, locale){
        replace = replace || [];
        locale = locale || "en";
        split = key.split('.');
        group = split.splice(0,1);
        item = split;
        if(this.KeyIsSet(this.dictionary, locale, group, item)){
            return this.dictionary[locale][group][item];
        }
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
    }
};

_lang=function(key, replace, locale) {
    replace = replace || [];
    locale = locale || "en";
    return LangJS.Get(key,replace,locale);
}
SCRIPT;
