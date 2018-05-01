var roleVue = new Vue ({
    el: '#roleVue',
    data: {
        roleList: [],
        permissionsDDL: [],
        mode: '',
        role: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getPermissions();
        this.getAllRole();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#roleCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.role.save').url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#roleCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#roleCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.role.edit', this.role.hId).url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        this.loadingPanel('#roleCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#roleCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllRole: function() {
            this.loadingPanel('#roleListBlock', 'TOGGLE');
            axios.get(route('api.get.settings.role.read').url()).then(response => {
                this.roleList = response.data;
                this.loadingPanel('#roleListBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#roleListBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.role = this.emptyRole();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.role = this.roleList[idx];
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.role = this.roleList[idx];
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.settings.role.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();
            this.getAllRole();
        },
        emptyRole: function() {
            return {
                hId: '',
                name: '',
                display_name: '',
                description: '',
                permissions: [],
                selectedPermissionIds: []
            }
        },
        getPermissions: function() {
            axios.get(route('api.get.settings.role.permission.read').url()).then(response => {
                this.permissionsDDL = response.data;
            }).catch(e => { this.handleErrors(e); });
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#roleListBlock', 'CLOSE')
                    this.contentPanel('#roleCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#roleListBlock', 'OPEN')
                    this.contentPanel('#roleCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return ''
        }
    }
});
