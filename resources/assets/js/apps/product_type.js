var productTypeVue = new Vue ({
    el: '#productTypeVue',
    data: {
        productTypeList: [],
        statusDDL: [],
        mode: '',
        productType: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllProductType();
        this.getLookupStatus();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.product.product_type.save').url(), new FormData($('#productTypeForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.product.product_type.edit', this.productType.hId).url(), new FormData($('#productTypeForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllProductType: function() {
            this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
            axios.get(route('api.get.product.product_type.read').url()).then(response => {
                this.productTypeList = response.data;
                this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#productTypeCRUDBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.productType = this.emptyProductType();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.productType = this.productTypeList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.productType = this.productTypeList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.product.product_type.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList : function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllProductType();
        },
        emptyProductType: function() {
            return {
                hId: '',
                companyHId: '',
                name: '',
                short_code: '',
                description: '',
                status: ''
            }
        },
        getLookupStatus: function() {
             axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#productTypeListBlock', 'CLOSE')
                    this.contentPanel('#productTypeCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#productTypeListBlock', 'OPEN')
                    this.contentPanel('#productTypeCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return '';
        },
    }
});