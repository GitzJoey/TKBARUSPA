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
                Codebase.blocks('#vendorTruckingCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post('/api/post/truck/vendor_trucking/save',
                        new FormData($('#vendorTruckingForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#vendorTruckingCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post('/api/post/truck/vendor_trucking/edit/' + this.vendorTrucking.hId,
                        new FormData($('#vendorTruckingForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#vendorTruckingCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else { }
            });
        },
        getAllVendorTrucking: function() {
            Codebase.blocks('#vendorTruckingListBlock', 'state_toggle');

            axios.get('/api/get/truck/vendor_trucking/read').then(response => {
                this.vendorTruckingList = response.data;
                Codebase.blocks('#vendorTruckingListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
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
            axios.post('/api/post/truck/vendor_trucking/delete/' + idx).then(response => {
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
            axios.get('/api/get/lookup/byCategory/STATUS').then(
                response => { this.statusDDL = response.data; }
            );
        },
        getBank: function() {
            axios.get('/api/get/bank/read').then(
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
                    Codebase.blocks('#vendorTruckingListBlock', 'close')
                    Codebase.blocks('#vendorTruckingCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#vendorTruckingListBlock', 'open')
                    Codebase.blocks('#vendorTruckingCRUDBlock', 'close')
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
