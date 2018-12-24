<?php
/**
 * Admin quiz editor: fib question answer template.
 *
 * @since 3.0.0
 */

?>

<script type="text/x-template" id="tmpl-lp-quiz-fib-question-answer">
    <div class="admin-quiz-fib-question-editor">
        <div class="lp-box-data-content">
            <div class="learn-press-question">
                <textarea :id="'fib-content-'+question.id" @blur="updateAnswer" v-model="question.answers[0].text"
                          placeholder='' @change="maybeUpdateEditor"></textarea>

                <p>
                    <button class="button" type="button"
                            @click="insertBlank"><?php _e( 'Insert new blank', 'learnpress-fill-in-blanl' ); ?></button>
                    <button class="button" type="button"
                            @click="clearBlanks"
                            v-bind:disabled="!blanks || blanks.length == 0"><?php _e( 'Clear all blanks', 'learnpress-fill-in-blanl' ); ?></button>
                </p>
                <div class="description">
                    <p><?php _e( 'Select a word and click button above to set a blank for filling.', 'learnpress-fill-in-blank' ); ?></p>
                </div>

                <table class="fib-blanks">
                    <tbody v-for="blank in blanks" :data-id="'fib-blank-' + blank.id" class="fib-blank"
                           v-bind:class="{ invalid: !blank.fill }">
                    <tr>
                        <td class="blank-position" width="50">#{{blank.index}}</td>
                        <td class="blank-fill">
                            <input type="text" :id="'fib-blank-' + blank.id" v-model="blank.fill"
                                   @keyup="updateBlank" @change="updateBlank">
                        </td>
                        <td class="blank-actions">
                            <span class="blank-status"></span>

                            <a class="button" href=""
                               @click="toggleOptions"><?php _e( 'Options', 'learnpress-fill-in-blank' ); ?></a>
                            <a class="delete button" href=""
                               @click="removeBlank"><?php _e( 'Delete', 'learnpress-fill-in-blank' ); ?></a>
                        </td>
                    </tr>
                    <tr class="blank-options">
                        <td width="50"></td>
                        <td colspan="2">
                            <ul>
                                <li>
                                    <label>
                                        <input type="checkbox" v-model="blank.match_case" @click="updateAnswer">
										<?php _e( 'Match case', 'learnpress-fill-in-blank' ); ?></label>
                                    <p class="description"><?php _e( 'Match two words in case sensitive', 'learnpress-fill-in-blank' ); ?></p>
                                </li>
                                <li><h3><?php _e( 'Comparison', 'learnpress-fill-in-blank' ); ?></h3></li>
                                <li>
                                    <label>
                                        <input type="radio" value="" v-model="blank.comparison" @click="updateAnswer">
										<?php _e( 'Equal', 'learnpress-fill-in-blank' ); ?></label>
                                    <p class="description"><?php _e( 'Match two words are equality.', 'learnpress-fill-in-blank' ); ?></p>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" value="range" v-model="blank.comparison"
                                               @click="updateAnswer">
										<?php _e( 'Range', 'learnpress-fill-in-blank' ); ?></label>
                                    <p class="description"><?php _e( 'Match any number in a range. Use <code>100, 200</code> will match any value from 100 and 200.', 'learnpress-fill-in-blank' ); ?></p>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" value="any" v-model="blank.comparison"
                                               @click="updateAnswer">
										<?php _e( 'Any', 'learnpress-fill-in-blank' ); ?></label>
                                    <p class="description"><?php _e( 'Match any value in a set of words. Use <code>fill, blank, question</code> will match any value in the set.', 'learnpress-fill-in-blank' ); ?></p>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    (function (Vue, $store, $) {
        var init = function() {
            FIB_Admin.initEditor(this, {
                selector: '#fib-content-' + this.question.id,
                insertTooltip: '<?php _e( 'Insert new blank', 'learnpress-fill-in-blank' );?>',
                clearTooltip: '<?php _e( 'Clear all blanks', 'learnpress-fill-in-blank' );?>',
                clearConfirm: '<?php _e( 'Do you want to remove all blanks???', 'learnpress-fill-in-blank' );?>',
                content_css: [
                    '<?php echo LP_Addon_Fill_In_Blank::instance()->get_plugin_url( 'assets/css/editor.css?r=' . microtime() );?>',
                    '<?php echo get_site_url();?>/wp-includes/css/dashicons.css'
                ],
                setup: function (editor) {
                    this.editor = editor;
                    var content = this.question.answers[0].text,
                        shortcodes = content.match(/\[fib.*?\]/g),
                        uids = {};
                    if (shortcodes) {
                        for (var i = 0; i < shortcodes.length; i++) {
                            var uid,
                                fill,
                                replaceText,
                                props = shortcodes[i].match(/([a-z_]+)="(.*?)"/g),
                                data = [];

                            for (var j in props) {
                                var prop = props[j].match(/([a-z_]+)="(.*?)"/);
                                if (!prop) {
                                    continue;
                                }

                                switch (prop[1]) {
                                    case 'uid':
                                    case 'id':
                                        uid = prop[2];
                                        break;
                                    case 'fill':
                                        fill = prop[2];
                                        break;
                                    default:
                                        data.push('data-' + prop[1] + '="' + prop[2] + '"')
                                }
                            }

                            uid = uid ? uid : LP.uniqueId();

                            if (uids[uid]) {
                                uid = LP.uniqueId();
                            }

                            replaceText = '<span class="fib-blank" id="fib-blank-' + uid + '" data-id="' + uid + '" data-fill="' + fill + '" ' + data.join(' ') + '>[#' + (i + 1) + ']</span>';
                            uids[uid] = true;

                            content = content.replace(shortcodes[i], replaceText);
                        }
                    }
                    this.parseBlanks(content)
                },
                onClear: this.onClear
            });
        }
        Vue.component('lp-quiz-fib-question-answer', {
            template: '#tmpl-lp-quiz-fib-question-answer',
            props: ['question'],
            data: function () {
                return {blanks: []}
            },
            computed: {
                answer: function () {
                    return {
                        answer_order: 1,
                        is_true: '',
                        question_answer_id: this.question.answers[0].question_answer_id,
                        text: this.question.answers[0].text,
                        value: ''
                    };
                }
            },
            mounted: function () {
                init.apply(this);
            },
            methods: {
                updateAnswer: function () {
                    var answer = JSON.parse(JSON.stringify(this.answer));
                    answer.text = this.getShortcode();
                    answer.blanks = this.getBlanksForDB();

                    $store.dispatch('lqs/updateQuestionAnswerTitle', {
                        question_id: this.question.id,
                        answer: answer
                    });
                },
                ///
                parseBlanks: function (content) {
                    var answer = this.question.answers[0],
                        blanks = [];

                    if (content !== undefined) {
                        this.question.answers[0].text = content;
                        this.answer.text = content;
                    } else {
                        content = answer.text;
                    }

                    var $container = $('<div />').html(content),
                        $inputs = $container.find('.fib-blank'),
                        i = 0, n = 0, data;

                    for (i = 0; i < $inputs.length; i++) {
                        data = $inputs.eq(i).data();
                        blanks.push({
                            fill: data.fill,
                            id: data.id,
                            comparison: data.comparison || '',
                            match_case: data.match_case || 0,
                            index: i + 1
                        })
                    }
                    this.blanks = blanks;
                    this.question.blanks = blanks;

                    setTimeout($.proxy(function (blanks, content) {
                        this.updateAnswer();
                        this.editor.setContent(content);
                    }, this), 300, blanks, $container.html())

                },
                updateBlanks: function (content) {
                    this.parseBlanks(content !== undefined ? content : this.editor.getContent());
                    return this.getShortcode();
                },
                getShortcode: function () {
                    var that = this,
                        $container = $('<div />').html(this.editor.getContent()),
                        $blanks = $container.find('.fib-blank');

                    $blanks.each(function () {
                        var $blank = $(this),
                            id = $blank.attr('id'),
                            uid = id.replace('fib-blank-', ''),
                            blank = that.getBlankById(uid),
                            code = 'fib';
                        if (blank) {
                            if (!blank.id) {
                                return;
                            }
                            for (var i in blank) {
                                if ($.inArray(i, ['index']) !== -1) {
                                    continue;
                                }

                                if (!blank[i]) {
                                    continue;
                                }

                                code += ' ' + i + '="' + blank[i] + '"';
                            }
                            $blank.replaceWith('[' + code + ']');
                        } else {
                            console.log('Not found: ' + uid)
                            $blank.replaceWith('')
                        }
                    });

                    return $container.html();
                },
                getBlankById: function (id) {
                    var blank = false;
                    $.each(this.blanks, function () {
                        if (id == this.id) {
                            blank = this;
                            return true;
                        }
                    });
                    return blank;
                },
                parseShortcode: function (content) {

                },
                updateBlank: function (e) {
                    var $el = $(e.target),
                        id = $el.attr('id'),
                        content = this.editor.getContent(),
                        $wrap = $('<div />').html(content),
                        $blank = $wrap.find('#' + id),
                        pos = $wrap.find('.fib-blank').index($blank) + 1;

                    $blank.attr('data-fill', e.target.value).html('[#' + pos + ']');

                    this.editor.setContent($wrap.html());
                       if(e.type == 'change'){
                       		this.editor.save();
                       }
                },
                removeBlank: function (e) {
                    var that = this,
                        $li = $(e.target).closest('.fib-blank'),
                        id = $li.attr('data-id'),
                        $container = $('<div />').html(this.editor.getContent()),
                        $blank = $container.find('.fib-blank#' + id);

                    $blank.replaceWith($blank.attr('data-fill'));
                    this.editor.setContent($container.html());
                    this.editor.save();

                    $(this.$el).find('.fib-blanks .blank-fill input').each(function () {
                        that.updateBlank({target: this});
                    });

                    var blanks = JSON.parse(JSON.stringify(this.blanks));
                    for (var i = 0; i < blanks.length; i++) {
                        if (blanks[i].id === id) {
                            blanks.splice(i, 1);
                            for (var j = 0; j < blanks.length; j++) {
                                blanks[j].index = j + 1;
                            }
                            break;
                        }
                    }

                    this.blanks = blanks;
                    this.updateAll();

                    e.preventDefault();
                },
                onClear: function () {
                    this.blanks = [];
                    this.updateAll();
                },
                updateAll: function () {
                    this.answer.text = this.updateBlanks();
                    this.updateAnswer();
                },
                insertBlank: function () {
                    this.editor.buttons['fib-code'].onclick();
                },
                clearBlanks: function () {
                    this.editor.buttons['fib-clear'].onclick();
                },

                getBlanksForDB: function () {
                    var blanks = {};
                    for (var i = 0, n = this.blanks.length; i < n; i++) {
                        var id = this.blanks[i].id.replace('fib-blank-', '');
                        blanks[id] = JSON.parse(JSON.stringify(this.blanks[i]));
                        blanks[id].id = id;
                    }
                    return blanks;
                },
                toggleOptions: function (e) {
                    e.preventDefault();
                    $(e.target).closest('.fib-blank').find('.blank-options ul').slideToggle()
                },
                maybeUpdateEditor: function () {

                }
            },
            ///
            created: function () {
                init.apply(this);
            }
        })
    })(Vue, LP_Quiz_Store, jQuery);

</script>
