var roleVue = new Vue ({
    el: '#roleVue',
    data: {
        roleList: [],
        permissionDDL: [],
        mode: '',
        role: { }
    },
    mounted: function () {
        this.mode = 'list';
        this.getAllRole();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(isValid => {
                if (!isValid) return;
                Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                if (this.mode == 'create') {
                    axios.post(route('api.post.settings.role.save').url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.settings.role.edit', this.role.hId).url(), new FormData($('#roleForm')[0])).then(response => {
                        this.backToList();
                        Codebase.blocks('#roleCRUDBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                } else { }
            });
        },
        getAllRole: function() {
            Codebase.blocks('#roleListBlock', 'state_toggle');
            axios.get(route('api.get.settings.role.read').url()).then(response => {
                this.roleList = response.data;
                Codebase.blocks('#roleListBlock', 'state_toggle');
            }).catch(e => { this.handleErrors(e); });
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
                permission: ''
            }
        },
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    Codebase.blocks('#roleListBlock', 'close')
                    Codebase.blocks('#roleCRUDBlock', 'open')
                    break;
                case 'list':
                default:
                    Codebase.blocks('#roleListBlock', 'open')
                    Codebase.blocks('#roleCRUDBlock', 'close')
                    break;
            }
        }
    }
});