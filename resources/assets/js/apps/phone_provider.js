var phoneProviderVue = new Vue ({
    el: '#phoneProviderVue',
    data: {
        phoneProviderList: [],
        mode: '',
        phone_provider: { },
        statusDDL: []
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllPhoneProvider();
        this.getLookupStatus();
    },
    methods: {
        validateBeforeSubmit : function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                Codebase.blocks('#phoneProviderCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.phone_provider.save').url(), new FormData($('#phoneProviderForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => { 
                        this.handleErrors(e); 
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.phone_provider.edit', this.phone_provider.hId).url(), new FormData($('#phoneProviderForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => { 
                        this.handleErrors(e);
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    });
                } else { }
            });
        },
        getAllPhoneProvider: function() {
            Codebase.blocks('#phoneProviderListBlock', 'state_toggle');
            axios.get(route('api.get.settings.phone_provider.read').url()).then(response => {
                this.phoneProviderList = response.data;
                Codebase.blocks('#phoneProviderListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew : function() {
            this.mode = 'create';
            this.phone_provider = this.emptyPhoneProvider();
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.phone_provider = this.phoneProviderList[idx];
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.phone_provider = this.phoneProviderList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.settings.phone_provider.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList : function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllPhoneProvider();
        },
        addNewPrefix : function() {
            this.phone_provider.prefixes.push({
                'phoneProviderHId': '',
                'prefix': ''
            });
        },
        removePrefix : function(idx) {
            this.phone_provider.prefixes.splice(idx, 1);
        },
        emptyPhoneProvider: function() {
            return {
                hId: '',
                name: '',
                short_name: '',
                status: '',
                remarks: '',
                prefixes: []
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
                    Codebase.blocks('#phoneProviderListBlock', 'close')
                    Codebase.blocks('#phoneProviderCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#phoneProviderListBlock', 'open')
                    Codebase.blocks('#phoneProviderCRUDBlock', 'close')
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