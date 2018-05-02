var customerVue = new Vue ({
    el: '#customerVue',
    data: {
        customer: {},
        customerList: [],
        statusDDL: [],
        bankDDL: [],
        providerDDL: [],
        mode: '',
        search_customer_query: '',
        active_page: 0,
        tableLoaded: false
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getBank();
        this.getPhoneProvider();
        this.getAllCustomer();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateScopes().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#customerCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.customer.save').url(),
                        new FormData($('#customerForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        this.loadingPanel('#customerCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#customerCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.customer.edit', this.customer.hId).url(),
                        new FormData($('#customerForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        this.loadingPanel('#customerCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#customerCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllCustomer: function(page) {
            this.loadingPanel('#customerListBlock', 'TOGGLE');

            var qS = [];
            if (this.search_customer_query) { qS.push({ 'key':'s', 'value':this.search_customer_query }); }
            if (page && typeof(page) == 'number') {
                this.active_page = page;
                qS.push({ 'key':'page', 'value':page });
            }

            axios.get(route('api.get.customer.read').url() + this.generateQueryStrings(qS)).then(response => {
                this.customerList = response.data;
                this.loadingPanel('#customerListBlock', 'TOGGLE');
            }).catch(e => { 
                this.handleErrors(e); 
                this.loadingPanel('#customerListBlock', 'TOGGLE'); 
            }); 
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.customer = this.emptyCustomer();
            this.getProduct();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.customer = this.customerList.data[idx];
            this.getProduct();
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.customer = this.customerList.data[idx];
            console.log(this.customer);
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.customer.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();

            if (this.active_page != 0 || this.active_page != 1) {
                this.getAllCustomer(this.active_page);
            } else {
                this.getAllCustomer();
            }
        },
        emptyCustomer: function() {
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
            this.customer.bank_accounts.push({
                bankHId: '',
                account_name: '',
                account_number: '',
                remarks: ''
            });
        },
        removeSelectedBank: function(idx) {
            this.customer.bank_accounts.splice(idx, 1);
        },
        addNewPIC: function() {
            this.customer.persons_in_charge.push({
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
            this.customer.persons_in_charge.splice(idx, 1);
        },
        addNewPhone: function(parentIndex) {
            if (!this.customer.persons_in_charge[parentIndex].hasOwnProperty('phone_numbers')) {
                this.customer.persons_in_charge[parentIndex].phone_numbers = [];
            }

            this.customer.persons_in_charge[parentIndex].phone_numbers.push({
                hId: '',
                phoneProviderHId: '',
                number: '',
                remarks: ''
            });
        },
        removeSelectedPhone: function(parentIndex, idx) {
            this.customer.persons_in_charge[parentIndex].phone_numbers.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS')).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getPhoneProvider: function() {
            axios.get(route('api.get.settings.phone_provider.read').url()).then(
                response => { this.providerDDL = response.data; }
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
                    this.contentPanel('#customerListBlock', 'CLOSE')
                    this.contentPanel('#customerCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#customerListBlock', 'OPEN')
                    this.contentPanel('#customerCRUDBlock', 'CLOSE')
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