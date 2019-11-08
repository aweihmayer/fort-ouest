Gallery = {
    create: function(linkSelector){
        var $this = this;
        var links = document.querySelectorAll(linkSelector);

        for(var i = 0; i < links.length; i++){
            links[i].addEventListener('click', function(){
                $this.open(this);
            });
        }
    },

    open: function(link){
        Lightbox.open({
            content: this.createImageFromLink(link)
        });
    },

    createImageFromLink: function(link){
        var img = document.createElement('img');
        var data = this.getLinkData(link);
        img.id = Lightbox.getId('img');
        img.src = data.src;

        if(typeof data.alt !== 'undefined'){
            img.alt = data.alt;
        }

        return img;
    },

    getLinkData: function(link){
        data = {};
        linkImage = link.getElementsByTagName('img');

        if(typeof link.dataset.src !== 'undefined'){
            data.src = link.dataset.src;
        }

        if(typeof link.dataset.alt !== 'undefined'){
            data.alt = link.dataset.alt;
        }

        if(linkImage.length != 0){
            linkImage = linkImage[0];

            if(typeof data.src === 'undefined'){
                data.src = linkImage.src;
            }

            if(typeof data.alt === 'undefined' && typeof linkImage.alt !== 'undefined'){
                data.alt = linkImage.alt;
            }
        }
        else if(link.tagName === 'img'){
            if(typeof data.src === 'undefined'){
                data.src = link.src;
            }

            if(typeof data.alt === 'undefined' && typeof link.alt !== 'undefined'){
                data.alt = link.alt;
            }
        }

        return data;
    }
};