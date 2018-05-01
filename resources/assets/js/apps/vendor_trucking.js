var vendorTruckingVue = new Vue ({
    el: '#vendorTruckingVue',
    data: {
        mode: '',
        statusDDL: [],
        bankDDL: [],
        vendorTrucking: {},
        vendorTruckingList: []
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getAllVendorTrucking();
        this.getBank();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateScopes().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#vendorTruckingCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.truck.vendor_trucking.save').url(),
                        new FormData($('#vendorTruckingForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#vendorTruckingCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#vendorTruckingCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.truck.vendor_trucking.edit', this.vendorTrucking.hId).url(),
                        new FormData($('#vendorTruckingForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#vendorTruckingCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#vendorTruckingCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllVendorTrucking: function() {
            this.loadingPanel('#vendorTruckingListBlock', 'TOGGLE');

            axios.get(route('api.get.truck.vendor_trucking.read').url()).then(response => {
                this.vendorTruckingList = response.data;
                this.loadingPanel('#vendorTruckingListBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#vendorTruckingListBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.vendorTrucking = this.emptyVendorTrucking();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.vendorTrucking = this.vendorTruckingList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.vendorTrucking = this.vendorTruckingList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.truck.vendor_trucking.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllVendorTrucking();
        },
        emptyVendorTrucking: function() {
            return {
                hId: '',
                name: '',
                address: '',
                tax_id: '',
                status: '',
                remarks: '',
                bank_accounts: []
            }
        },
        addBankAccounts: function() {
            this.vendorTrucking.bank_accounts.push({
                'bankHId': '',
                'account_name': '',
                'account_number': '',
                'remarks': ''
            });
        },
        removeSelectedBankAccounts: function(idx) {
            this.vendorTrucking.bank_accounts.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS').url()).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getBank: function() {
            axios.get(route('api.get.bank.read').url()).then(
                response => { this.bankDDL = response.data; }
            );
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#vendorTruckingListBlock', 'CLOSE')
                    this.contentPanel('#vendorTruckingCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#vendorTruckingListBlock', 'OPEN')
                    this.contentPanel('#vendorTruckingCRUDBlock', 'CLOSE')
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
