(function () {
    tinymce.PluginManager.requireLangPack("lao3d_personal_edition"); tinymce.create("tinymce.plugins.Lao3dPersonalEdition", { init: function (a, b) {
        a.addCommand("lao3dPersonal", function () { a.windowManager.open({ file: b + "/dialog.htm", width: 620 + a.getLang("lao3d_personal_edition.delta_width", 0), height: 300 + a.getLang("lao3d_personal_edition.delta_height", 0), inline: 1 }, { plugin_url: b, some_custom_arg: "custom arg" }) }); a.addButton("lao3d_personal_edition", { title: "lao3d_personal_edition.desc", cmd: "lao3dPersonal",
            image: b + "/lao3d-personal-edition-icon.png"
        }); a.onNodeChange.add(function (a, b, c) { b.setActive("lao3d_personal_edition", "IMG" == c.nodeName) })
    }, createControl: function (a, b) { return null }, getInfo: function () { return { longname: "Lao3d Personal Edition", author: "wxstorm", authorurl: "http://www.lao3d.com/", infourl: "http://www.lao3d.com/", version: "1.0"} } 
    }); tinymce.PluginManager.add("lao3d_personal_edition", tinymce.plugins.Lao3dPersonalEdition)
})();