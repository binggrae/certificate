"use strict";

// =============================== App


class Editor {
    constructor() {
        Editor.setAjax(1);
        this.fontManager = new FontManager(this);
        this.blockManager = new BlockManaged(this);

        this.fontManager.promise.then(
            response => {
                this._defaulFont = this.fontManager.fonts[this.fontManager.fonts.length - 1].id;
            }
        );


    }

    get defaultFont() {
        return this._defaulFont;
    }

    set defaultFont(font) {
        this._defaulFont = font;
    }

    static ucFirst(str) {
        if (!str) return str;

        return str[0].toUpperCase() + str.slice(1);
    }

    static renderTpl(tpl, variables) {
        for (let i in variables) {
            if (variables.hasOwnProperty(i)) {
                tpl = tpl.split('{' + i + '}').join(variables[i]);
            }
        }
        return tpl;
    }

    static setAjax(run) {
        let $el = $("#ajaxLoader");
        if (run) {
            $el.show();
        } else {
            $el.hide();
        }
    }

    static offset(type) {
        return $('#jsBlockContainer').offset()[type];
    };
}

// =============================== Font
class FontManager {
    constructor(app) {
        this.app = app;
        this.fonts = [];

        this.promise = new Promise((resolve, reject) => {
            $.ajax('/font/load', {
                type: 'get',
                dataType: 'json',
                success: (response) => {
                    resolve(response);
                }
            });
        });

        this.promise.then(
            response => {
                for (let font of response) {
                    this.fonts.push(new Font(font));
                }
            }
        )
    }

    getById(id) {
        for (let font of this.fonts) {
            if (font.id === id) {
                return font;
            }
        }
        return null;
    }

    getList() {
        return this.fonts;
    }
}


class Font {
    constructor({id, name, ttf}) {
        this.id = id;
        this.name = name;
        this.ttf = ttf;

        this.render();
    }

    render() {
        let tpl = $('#tplFontFace').html();
        tpl = Editor.renderTpl(tpl, {
            id: this.id,
            name: this.name,
            ttf: this.ttf,
        });

        let $el = $(tpl);
        $el.appendTo($('head'));
    }
}

// =============================== BlockManager

class BlockManaged {
    constructor(app) {
        this.app = app;

        this.types = [];
        this.blocks = [];
        this.id = $('#Editor').data('id');

        this.setCreateTypeCallback();
        this.loadStructure(this.id);
    }


    loadStructure(id) {
        this.app.fontManager.promise.then(
            response => {
                $.ajax('/admin/structure', {
                    type: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: (data) => {
                        for (let type of data.types) {
                            this.installType(type);
                        }
                        for (let block of data.blocks) {
                            for (let type of this.types) {
                                if (parseInt(type.id) === parseInt(block.typeId)) {
                                    this.installBlock(type.createBlock(block));

                                    break;
                                }
                            }
                        }
                        Editor.setAjax(0);
                    }
                })

            }
        );
    }

    setCreateTypeCallback() {
        $('#jsCreateFieldBlock').on('click', (e) => {
            e.preventDefault();
            let field = prompt('Введите название поля');
            if(!field) {
                return;
            }
            Editor.setAjax(1);
            $.ajax('/admin/create-type', {
                type: 'post',
                data: {field: field},
                dataType: 'json',
                success: (data) => {
                    if (data) {
                        this.installType(data);
                    }
                    Editor.setAjax(0);
                }
            })
        });
    }

    installType({id, type, title}) {
        let _type = new types[Editor.ucFirst(type) + 'Type']({id, type, title});
        _type.installBlock = (block) => {
            this.installBlock(block)
        };

        this.types.push(_type);
    }

    installBlock(block) {
        block.id = this.getBlockId();
        block.fontId = block.fontId === 0 ? this.app.defaultFont : block.fontId;
        block.render();

        block.$panel = this.renderPanel(block);

        this.blocks.push(block);
    }


    renderPanel(block) {
        let tpl = $('#tplPanelBlock').html();
        tpl = Editor.renderTpl(tpl, block.variables);

        let $panel = $(tpl);
        $panel.appendTo($('#jsPanelBlocks'));

        $panel.find('.jsInputTitle').on('change', (e) => {
            block.setTitle($(e.target).val());
        });

        $panel.find('.jsInputFontSize').on('change', (e) => {
            block.setFontSize($(e.target).val());
        });

        $panel.find('.jsInputColor')
            .colorPicker({pickerDefault: "ffffff"})
            .on('change', (e) => {
                block.setColor($(e.target).val());
            });

        for (let font of this.app.fontManager.getList()) {
            let html = $('#tplFont').html();
            let el = Editor.renderTpl(html, font);

            $(el).appendTo($panel.find('.jsSelectFontId'));
        }
        $panel.find('.jsSelectFontId').val(block.fontId);

        $panel.find('.jsSelectFontId').on('change', (e) => {
            let val = $(e.target).val();
            this.app.defaultFont = val;
            block.setFont(val);
        });


        return $panel;
    }


    getBlockId() {
        return ++this.id;
    }


}

// =========================================

class Type {
    constructor({id, type, title}) {
        this.id = id;
        this.type = type;
        this.title = title;

        this.render();
    }

    render() {
        let tpl = $('#tplPanelType').html();
        tpl = Editor.renderTpl(tpl, {
            id: this.id,
            type: this.type,
            title: this.panelTitle,
        });

        let $el = $(tpl);
        $el.appendTo($('#jsPanelTypes').find('tbody'));

        $el.find('.jsAddBlock').on('click', (e) => {
            e.preventDefault();
            this.installBlock(this.createBlock());
        });
    }


    get panelTitle() {
        return this.title;
    }

    get valueTitle() {
        return this.title;
    }

    createBlock(block) {
        return new Block(this, block);
    }
}

class IdType extends Type {
    createBlock(block = {}) {
        return new IdBlock(this, block);
    }

    get panelTitle() {
        return this.title + ' *';
    }
}

class FieldType extends Type {
    createBlock(block = {}) {
        return new FieldBlock(this, block);
    }
}

class DayType extends Type {
    createBlock(block = {}) {
        return new DayBlock(this, block);
    }

    get panelTitle() {
        return this.title + ' *';
    }

    get valueTitle() {
        return new Date().toLocaleString('ru', {
            day: 'numeric'
        });
    }
}

class MonthType extends Type {
    createBlock(block = {}) {
        return new MonthBlock(this, block);
    }

    get panelTitle() {
        return this.title + ' *';
    }

    get valueTitle() {
        return new Date().toLocaleString('ru', {
            day: 'numeric',
            month: 'long',
        }).replace(/(\d+\s)/, '');
    }
}

class YearType extends Type {
    createBlock(block = {}) {
        return new YearBlock(this, block);
    }

    get panelTitle() {
        return this.title + ' *';
    }

    get valueTitle() {
        return new Date().toLocaleString('ru', {
            year: 'numeric',
        });
    }
}

class DateType extends Type {
    createBlock(block = {}) {
        return new DateBlock(this, block);
    }

    get panelTitle() {
        return this.title + ' *';
    }

    get valueTitle() {
        return new Date().toLocaleString('ru', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).replace(' г.', '');

    }
}

let types = {
    IdType,
    FieldType,
    DayType,
    MonthType,
    YearType,
    DateType,
};

// =========================================

class Block {
    constructor(type, {posX = 0, posY = 0, uid = 0, width = 100, fontId = 0, fontSize = 20, color = '#000'}) {
        this.$panel = null;

        console.log(type);

        this.type = type;
        this.id = null;

        this.posX = posX;
        this.posY = posY;
        this.title = type.valueTitle;
        this.uid = uid;
        this.width = width;
        this.fontId = fontId;
        this.fontSize = fontSize;
        this.color = color;
    }

    render() {
        let tpl = $('#tplBlock').html();
        tpl = Editor.renderTpl(tpl, this.variables);

        this.$el = $(tpl);
        this.$el.appendTo($('#jsBlockContainer'));

        this.$el.draggable({
            containment: '#jsBlockContainer',
            stop: () => this.drag(),
            create: () => this.drag()
        });

        this.$el.resizable({
            resize: (event, ui) => {
                ui.size.height = ui.originalSize.height;
                this.setWidth(ui.size.width);
            }
        });
    }

    get variables() {
        return {
            id: this.id,
            posX: this.posX,
            posY: this.posY,
            title: this.title,
            uid: this.uid,
            width: this.width,
            fontSize: this.fontSize,
            fontId: this.fontId,
            color: this.color,
            typeId: this.type.id,
        }
    }

    drag() {
        this.setPosition(this.$el.offset());
    };

    setWidth(width) {
        this.$el.find('.jsInputWidth').val(width);
        this.width = width;
    };

    setPosition(pos) {
        let posX = pos.left - Editor.offset('left');
        let posY = pos.top - Editor.offset('top');
        this.$el.find('.jsInputPosX').val(posX);
        this.$el.find('.jsInputPosY').val(posY);
        this.posX = posX;
        this.posY = posY;
    };

    setTitle(title) {
        this.title = title;
        this.$el.find('.jsTitle').text(title);
    };

    setFont(font) {
        this.fontId = font;
        this.$el.css({fontFamily: "font" + font});
        this.$el.find('.jsInputFontId').val(font);
    };

    setFontSize(size) {
        this.fontSize = size;
        this.$el.css({fontSize: size + 'px'});
        this.$el.find('.jsInputFontSize').val(size);
    };

    setColor(color) {
        this.color = color;
        this.$el.css({color: color});
        this.$el.find('.jsInputColor').val(color);
    }
}


class IdBlock extends Block {
}

class FieldBlock extends Block {
}

class DayBlock extends Block {
}

class MonthBlock extends Block {
}

class YearBlock extends Block {
}

class DateBlock extends Block {
}

// =========================================


$(document).ready(function () {
    let app = new Editor();

});