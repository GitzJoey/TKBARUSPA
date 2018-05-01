var unitVue = new Vue ({
    el: '#unitVue',
    data: {
        unitList: [],
        statusDDL: [],
        mode: '',
        unit: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllUnit();
        this.getLookupStatus();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#unitCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.unit.save').url(), new FormData($('#unitForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#unitCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#unitCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.unit.edit', this.unit.hId).url(), new FormData($('#unitForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#unitCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#unitCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllUnit: function() {
            this.loadingPanel('#unitListBlock', 'TOGGLE');
            axios.get(route('api.get.settings.unit.read').url()).then(response => {
                this.unitList = response.data;
                this.loadingPanel('#unitListBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#unitListBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.unit = this.emptyUnit();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.unit = this.unitList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.unit = this.unitList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.settings.unit.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllUnit();
        },
        emptyUnit: function() {
            return {
                hId: '',
                name: '',
                symbol: '',
                status: '',
                remarks: ''
            }
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#unitListBlock', 'CLOSE')
                    this.contentPanel('#unitCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#unitListBlock', 'OPEN')
                    this.contentPanel('#unitCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultStatus: function() {
            return '';
        }
    }
});
