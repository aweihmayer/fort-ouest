Lightbox = {
    id: {
        overlay: 'lb-overlay',
        outter: 'lb-outter',
        inner: 'lb-inner',
        box: 'lb-box',
        close: 'lb-close',
        img: 'lb-image'
    },

    createWindow: function(){
        if(byId(this.getId('overlay')) === null){
            var $this = this;

            var overlay = document.createElement('div');
            overlay.id = this.getId('overlay');
            overlay.style.display = 'none';

            var inner = '<div id="' + this.getId('overlay') + '">' +
                '<div id="' + this.getId('outter') + '">' +
                '<div id="' + this.getId('inner') + '">' +
                '<div id="' + this.getId('box') + '"></div>' +
                '</div>' +
                '</div>' +
                '<span id="' + this.getId('close') + '">X</span>' +
                '</div>';
            overlay.innerHTML = inner;

            document.body.appendChild(overlay);

            overlay.addEventListener('click', function(ev){
                $this.onClick(ev);
            });
            window.addEventListener("keydown", function(ev){
                var escapeKeys = [27, 8];

                if(escapeKeys.indexOf(ev.keyCode) !== 1){
                    $this.close();
                }
            });
        }
    },

    onClick: function(ev){
        switch(ev.target.id){
            case this.getId('overlay'):
            case this.getId('inner'):
            case this.getId('close'):
                this.close();
                break
        }
    },

    open: function(options){
        this.createWindow();
        this.empty();

        var overlay = byId(this.getId('overlay'));
        var box = byId(this.getId('box'));

        (typeof options.content === 'string') ? box.innerHTML = options.content : box.appendChild(options.content);

        overlay.style.display = 'block';
    },

    empty: function(){
        byId(this.getId('box')).innerHTML = '';
    },

    hide: function(){ byId(this.getId('overlay')).style.display = 'none'; },

    close: function(ev){
        this.hide();
        this.empty();
    },

    getId: function(k){ return this.id[k]; }
};