// Galerie Accueil
jQuery(document).ready(function($)
{
    updateGallery();
    var offset = 0;
    var perPage = 8;
    function updateGallery(category = '', format = '', sortOrder = 'recent', reset = false)
    {
        if (reset)
        {
            offset = 0;
        }

        performAjax(category, format, sortOrder, reset);
    }
    function performAjax(category, format, sortOrder, reset)
    {
        $.ajax(
        {
            url: ajax['ajax-url'],
            type: 'POST',
            data:
            {
                action: 'filter_photos',
                category: category,
                format: format,
                order: sortOrder,
                offset: offset
            },
            success: function(response)
            {
                var $newElements = $(response).hide().fadeIn('fast');

                if (reset)
                {
                    $('.home-photos .gallery').empty();
                }

                $('.home-photos .gallery').append($newElements);

                if (!$newElements.filter('.btn.more').length) {
                    $('.btn.more').hide();
                }
                else
                {
                    $('.btn.more').off('click').show().on('click', function()
                    {
                        updateGallery(category, format, sortOrder);
                    });
                }
                offset += perPage;
            }
        });
    }

    $('.dropdown.category, .dropdown.format').on('click', 'a', function(e)
    {
        e.preventDefault();
        $(this).toggleClass('active');
        var category = $('.dropdown.category a.active').map(function() {
            return $(this).data('category');
        }).get().join(',');
        var format = $('.dropdown.format a.active').map(function() {
            return $(this).data('format');
        }).get().join(',');
        var sortOrder = $('.dropdown.date a.active').data('sort') || 'recent';
        updateGallery(category, format, sortOrder, true);
    });

    $('.dropdown.date').on('click', 'a', function(e)
    {
        e.preventDefault();
        $('.dropdown.date a').removeClass('active');
        $(this).addClass('active');
        var sortOrder = $(this).data('sort') || 'recent';
        var category = $('.dropdown.category a.active').map(function() {
            return $(this).data('category');
        }).get().join(',');
        var format = $('.dropdown.format a.active').map(function() {
            return $(this).data('format');
        }).get().join(',');
        updateGallery(category, format, sortOrder, true);
    });
});

// Lightbox
jQuery(document).ready(function($)
{
    $('body').on('click', '.icon.fullscreen', function()
    {
        var item = $(this).closest('.gallery-item');
        var imageUrl = item.attr('data-image-url');
        var imageAlt = item.attr('data-image-alt');
        var imageRef = item.attr('data-image-ref');
        var imageCat = item.attr('data-image-cat');
        var postId = item.attr('data-post-id');
        var lightboxImage = $('.lightbox-image');
        var lightboxRef = $('.lightbox-ref');
        var lightboxCat = $('.lightbox-cat');
        lightboxImage.attr('src', imageUrl);
        lightboxImage.attr('alt', imageAlt);
        lightboxImage.attr('data-post-id', postId);
        lightboxRef.text(imageRef);
        lightboxCat.text(imageCat);
        $('#content-lightbox').css('display', 'flex');
    });
    $('.lightbox-close').on('click', function()
    {
        $('#content-lightbox').css('display', 'none');
    });
    $('.lightbox-control.left, .lightbox-control.right').click(function()
    {
        var currentPostId = $('.lightbox-image').data('post-id');
        var direction = $(this).hasClass('left') ? 'prev' : 'next';
        loadImage(currentPostId, direction);
    });
    function loadImage(postId, direction)
    {
        $.ajax(
        {
            url: ajax['ajax-url'],
            type: 'POST',
            dataType: 'json',
            data:
            {
                action: 'load_image',
                post_id: postId,
                direction: direction
            },
            success: function(data)
            {
                if (data.image_url && data.image_url !== "false")
                {
                    $('.lightbox-image').attr('src', data.image_url);
                    $('.lightbox-image').attr('alt', data.image_alt);
                    $('.lightbox-image').data('post-id', data.post_id);
                    $('.lightbox-ref').text(data.image_ref);
                    $('.lightbox-cat').text(data.image_cat);
                }
            }
        });
    }
});