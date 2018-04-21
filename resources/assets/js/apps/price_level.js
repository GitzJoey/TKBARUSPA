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
                Codebase.blocks('#priceLevelCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.price.price_level.save').url(), new FormData($('#priceLevelForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#priceLevelCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#priceLevelCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.price.price_level.edit', this.priceLevel.hId).url(),
                        new FormData($('#priceLevelForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#priceLevelCRUDBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                        Codebase.blocks('#priceLevelCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllPriceLevel: function(page) {
            Codebase.blocks('#priceLevelListBlock', 'state_toggle');
            axios.get(route('api.get.price.price_level.read').url()).then(response => {
                this.priceLevelList = response.data;
                Codebase.blocks('#priceLevelListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
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
                    Codebase.blocks('#priceLevelListBlock', 'close')
                    Codebase.blocks('#priceLevelCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#priceLevelListBlock', 'open')
                    Codebase.blocks('#priceLevelCRUDBlock', 'close')
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
