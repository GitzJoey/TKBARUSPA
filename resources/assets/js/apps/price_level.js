var priceLevelVue = new Vue ({
    el: '#priceLevelVue',
    data: {
        mode: '',
        statusDDL: [],
        priceLevelTypeDDL: [],
        priceLevel: {},
        priceLevelList: []
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getPriceLevelType();
        this.getAllPriceLevel();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#priceLevelCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.price.price_level.save').url(), new FormData($('#priceLevelForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#priceLevelCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#priceLevelCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.price.price_level.edit', this.priceLevel.hId).url(),
                        new FormData($('#priceLevelForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#priceLevelCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#priceLevelCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllPriceLevel: function(page) {
            this.loadingPanel('#priceLevelListBlock', 'TOGGLE');
            axios.get(route('api.get.price.price_level.read').url()).then(response => {
                this.priceLevelList = response.data;
                this.loadingPanel('#priceLevelListBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#priceLevelListBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.priceLevel = this.emptyPriceLevel();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.priceLevel = this.priceLevelList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.priceLevel = this.priceLevelList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.price.price_level.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllPriceLevel();
        },
        emptyPriceLevel: function() {
            return {
                hId: '',
                type: '',
                weight: '',
                name: '',
                description: '',
                increment_value: '',
                percentage_value: '',
                status: ''
            }
        },
        setIncReadOnly: function(type) {
            if (this.priceLevel.type == '') return false;

            if (this.priceLevel.type == 'PRICELEVELTYPE.INC') {
                return false;
            } else {
                return true;
            }
        },
        onPriceLevelSelect: function(type) {
            if (this.priceLevel.type == '') return;

            if (this.priceLevel.type == 'PRICELEVELTYPE.INC') {
                this.priceLevel.percentage_value = 0;
            } else {
                this.priceLevel.increment_val = 0;
            }
        },
        setPctReadOnly: function(type) {
            if (this.priceLevel.type == '') return false;

            if (this.priceLevel.type == 'PRICELEVELTYPE.PCT') {
                return false;
            } else {
                return true;
            }
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getPriceLevelType: function() {
            axios.get(route('api.get.lookup.bycategory', 'PRICELEVEL_TYPE').url()).then(
                response => { this.priceLevelTypeDDL = response.data; }
            );
        },
        getValue: function(priceLevel) {
            if (priceLevel.type == 'PRICELEVELTYPE.PCT') {
                return priceLevel.percentage_value + '%';
            } else if (priceLevel.type == 'PRICELEVELTYPE.INC') {
                return priceLevel.increment_value;
            } else { }
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#priceLevelListBlock', 'CLOSE')
                    this.contentPanel('#priceLevelCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#priceLevelListBlock', 'OPEN')
                    this.contentPanel('#priceLevelCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return '';
        }
    }
});
