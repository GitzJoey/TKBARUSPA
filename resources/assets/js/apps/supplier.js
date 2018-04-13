var supplierVue = new Vue ({
    el: '#supplierVue',
    data: {
        supplier: {},
        supplierList: [],
        statusDDL: [],
        bankDDL: [],
        providerDDL: [],
        productList: [],
        mode: '',
        search_supplier_query: '',
        active_page: 0,
        tableLoaded: false
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getBank();
        this.getPhoneProvider();
        this.getAllSupplier();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateScopes().then(isValid => {
                if (!isValid) return;
                Codebase.blocks('#supplierCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post('/api/post/supplier/save',
                        new FormData($('#supplierForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post('/api/post/supplier/edit/' + this.supplier.hId,
                        new FormData($('#supplierForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        Codebase.blocks('#companyCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else { }
                Codebase.blocks('#supplierCRUDBlock', 'state_toggle');
            });
        },
        getAllSupplier: function(page) {
            Codebase.blocks('#supplierListBlock', 'state_toggle');

            var qS = [];
            if (this.search_supplier_query) { qS.push({ 'key':'s', 'value':this.search_supplier_query }); }
            if (page && typeof(page) == 'number') {
                this.active_page = page;
                qS.push({ 'key':'page', 'value':page });
            }

            axios.get('/api/get/supplier/read' + this.generateQueryStrings(qS)).then(response => {
                this.supplierList = response.data;
                Codebase.blocks('#supplierListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.supplier = this.emptySupplier();
            this.getProduct();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.supplier = this.supplierList.data[idx];
            this.getProduct();
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.supplier = this.supplierList.data[idx];
            this.getProduct();
        },
        deleteSelected: function(idx) {
            axios.post('/api/post/supplier/delete/' + idx).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();

            if (this.active_page != 0 || this.active_page != 1) {
                this.getAllSupplier(this.active_page);
            } else {
                this.getAllSupplier();
            }
        },
        emptySupplier: function() {
            return {
                hId: '',
                name: '',
                code_sign: '',
                address: '',
                city: '',
                phone_number: '',
                fax_num: '',
                tax_id: '',
                status: '',
                remarks: '',
                payment_due_day: '',
                bank_accounts: [],
                persons_in_charge: [],
                products: [],
                listSelectedProductHId: []
            }
        },
        addNewBankAccount: function() {
            this.supplier.bank_accounts.push({
                bankHId: '',
                account_name: '',
                account_number: '',
                remarks: ''
            });
        },
        removeSelectedBank: function(idx) {
            this.supplier.bank_accounts.splice(idx, 1);
        },
        addNewPIC: function() {
            this.supplier.persons_in_charge.push({
                hId: '',
                first_name: '',
                last_name: '',
                email: '',
                address: '',
                ic_num: '',
                image_filename: '',
                phone_numbers:[{
                    hId: '',
                    phoneProviderHId: '',
                    number: '',
                    remarks: ''
                }]
            });
        },
        removeSelectedPIC: function(idx) {
            this.supplier.persons_in_charge.splice(idx, 1);
        },
        addNewPhone: function(parentIndex) {
            if (!this.supplier.persons_in_charge[parentIndex].hasOwnProperty('phone_numbers')) {
                this.supplier.persons_in_charge[parentIndex].phone_numbers = [];
            }

            this.supplier.persons_in_charge[parentIndex].phone_numbers.push({
                hId: '',
                phoneProviderHId: '',
                number: '',
                remarks: ''
            });
        },
        removeSelectedPhone: function(parentIndex, idx) {
            this.supplier.persons_in_charge[parentIndex].phone_numbers.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get('/api/get/lookup/byCategory/STATUS').then(
                response => { this.statusDDL = response.data; }
            );
        },
        getPhoneProvider: function() {
            axios.get('/api/get/phone_provider/read').then(
                response => { this.providerDDL = response.data; }
            );
        },
        getBank: function() {
            axios.get('/api/get/bank/read').then(
                response => { this.bankDDL = response.data; }
            );
        },
        getProduct: function(page) {
            this.tableLoaded = false;
            var qS = [];
            if (page && typeof(page) == 'number') { qS.push({ 'key':'page', 'value':page }); }

            axios.get('/api/get/product/read' + this.generateQueryStrings(qS)).then(
                response => {
                    this.productList = response.data;

                    for (var i = 0; i < this.productList.data.length; i++) {
                        if (_.includes(this.supplier.listSelectedProductHId, this.productList.data[i].hId)) {
                            this.productList.data[i].checked = true;
                        }
                    }

                    this.tableLoaded = true;
                }
            );
        },
        syncToSupplierProd: function(pLIdx) {
            if (this.productList.data[pLIdx].checked) {
                this.supplier.listSelectedProductHId.push(this.productList.data[pLIdx].hId);
            } else {
                _.pull(this.supplier.listSelectedProductHId, this.productList.data[pLIdx].hId);
            }
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#supplierListBlock', 'close')
                    Codebase.blocks('#supplierCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#supplierListBlock', 'open')
                    Codebase.blocks('#supplierCRUDBlock', 'close')
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
