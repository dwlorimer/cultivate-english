;(function ($) {
    window.FIB_Admin = {
        initEditor: function (instance, args) {
            args = $.extend({
                selector: '',
                content_css: '',
                insertTooltip: 'Insert blank',
                clearTooltip: 'Clear blanks',
                clearConfirm: 'Do you want to remove all blanks?',
                setup: function () {

                }
            }, args || {});
            tinymce.init({
                selector: args.selector,
                menubar: false,
                toolbar: 'fib-code fib-clear',
                statusbar: false,
                content_css: args.content_css,
                content_style: '',
                setup: function (editor) {

                    var index = 0;

                    function updateBlanks() {
                        instance.updateBlanks(editor.getContent());
                    }

                    function updateIndexBlanks() {
                        var content = editor.getContent(),
                            $content = $('<div />').html(content),
                            $blanks = $content.find('.fib-blank');

                        for (var i = 0; i < $blanks.length; i++) {
                            $blanks.eq(i).html('[#' + (i + 1) + ']')
                        }
                        editor.setContent($content.html())
                    }

                    function insertCode() {
                        var fillContent = editor.selection.getContent({format: 'text'}),
                            uid = LP.uniqueId(),
                            html = '<span id="fib-blank-' + uid + '" data-id="' + uid + '" class="fib-blank" data-fill="' + fillContent + '">'
                                + '</span>';

                        editor.insertContent(html);

                        updateIndexBlanks();

                        editor.save();

                        updateBlanks();

                        setTimeout(function () {
                            $('#fib-blank-' + uid).focus();
                        }, 400)
                    }

                    function clear() {

                        if (!confirm(args.clearConfirm)) {
                            return;
                        }

                        var $container = $('<div >').html(editor.getContent());

                        $container.find('.fib-blank').each(function () {
                            var $blank = $(this),
                                fill = $blank.attr('data-fill');

                            $blank.replaceWith(fill);
                        });

                        editor.setContent($container.html());

                        args.onClear && args.onClear.apply(instance, []);
                    }

                    editor.addButton('fib-code', {
                        icon: 'insert-blank',
                        tooltip: args.insertTooltip,
                        text: 'Insert new blank',
                        onclick: insertCode
                    });

                    editor.addButton('fib-clear', {
                        icon: 'clear-blanks',
                        tooltip: args.clearTooltip,
                        text: 'Clear all blanks',
                        onclick: clear
                    });

                    editor.on('change', function (e) {
                        updateBlanks();
                    });

                    args.setup && args.setup.apply(instance, [editor]);
                }
            });
        },
        vueComponent: {
            methods: {}
        }
    }

})(jQuery);