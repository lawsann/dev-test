define([
    'jquery',
    'uiComponent',
    'loader'
], function (
    $,
    Component
) {
    'use strict';

    return Component.extend({
        defaults: {
            currentAnimal: 'cat',
            imageSrc: '',
            animalOptions: [],
            template: 'Razoyo_AnimalProfile/profile/view'
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()
                .observe([
                    'currentAnimal',
                    'imageSrc',
                    'animalOptions'
                ]);

            return this;
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();
            this._reloadImage(this.currentAnimal());
            this.currentAnimal.subscribe(this._reloadImage.bind(this));
        },

        /**
         * Consults the image content on server, depending on
         * configured animal
         * @param string animal 
         */
        _reloadImage: function(animal) {
            if (this.pictureWrapperElement) {
                this.pictureWrapperElement.loader('show');
            }
            
            $.ajax({
                url: this.photoBaseUrl + '?animal=' + animal,
                type: 'GET'
            }).done(function(response) {
                this.imageSrc(response.photo);

                if (this.pictureWrapperElement) {
                    this.pictureWrapperElement.loader('hide');
                }
            }.bind(this));
        },

        setupLoading: function(element) {
            this.pictureWrapperElement = $(element);
            this.pictureWrapperElement.loader();
        }
    });
});
