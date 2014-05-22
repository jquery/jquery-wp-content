/*! Random book selector (c) 2014 Dan Wellman - danwellman.co.uk
    license: http://www.opensource.org/licenses/mit-license.php
*/
(function ($, undefined) {
    'use strict';

    var shuffle, createWidget, buildList, createListItem,
        defaultConfig = {
            numberOfBooks: 3,
            selector: '#books',
            title: 'Books',
            imgPath: '/content/books/',
            urlPrefix: ''
        };

    window.randomImageSelector = window.randomImageSelector || {};

    window.randomImageSelector.init = function init(data, userConfig) {

        var config = $.extend({}, defaultConfig, userConfig),
            shuffledData = shuffle(data),
            bookData = shuffledData.slice(0, config.numberOfBooks),
            $widget = createWidget(config.title).append(buildList(bookData, config)),
            $container = $(config.selector);

        $container.append($widget);
    };

    shuffle = function shuffle(array) {
        var temporaryValue, randomIndex,
            currentIndex = array.length;

        while (0 !== currentIndex) {

            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;
        }

        return array;
    };

    createWidget = function createWidget(name) {
        return $('<div/>', {
            html: ['<h3><span>', name, '</span></h3>'].join('')
        });
    };

    buildList = function buildList(bookData, config) {
        var $list = $('<ul/>', {
            'class': 'books'
        });

        $.each(bookData, function (i, itemData) {
            $list.append(createListItem(itemData, config));
        });

        return $list;
    };

    createListItem = function createListItem(itemData, config) {
        var $listItem = $('<li/>'),
            $anchor = $('<a/>', {
                href: itemData.link,
                text: itemData.title
            }).appendTo($listItem);

        $('<img/>', {
            src: [config.urlPrefix, config.imgPath, itemData.imgSrc].join(''),
            width: 92,
            height: 114,
            alt: [itemData.title, ' by ', itemData.authors].join()
        }).prependTo($anchor);

        $('<cite/>', {
            text: itemData.authors
        }).appendTo($anchor);

        return $listItem;
    };

}(jQuery));