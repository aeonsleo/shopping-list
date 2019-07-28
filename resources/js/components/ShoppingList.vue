<template>
    <div class="container">
        <div class="row" v-if="renderType=='list'">
            <div class="col-sm-12 pt-5">
                <h4>Shopping Lists</h4>
                <ul class="list-group pb-4">
                    <li v-for="(list,index) in shoppingLists" :key="list.id" class="list-group-item">
                        <div class="col-sm-1 float-left">
                            {{ index+1 }}
                        </div>
                        <div class="col-sm-9 float-left">
                            <a v-if="editListOffset!=index" href="javascript:void(0)" v-on:click="getList(list.id)">{{ list.name }}</a>
                            <div id="myId" ref="myId"></div>
                            <input type="text" 
                                v-if="editListOffset==index" 
                                @keyup.enter="updateList"
                                v-on:blur="cancelEdit"
                                class="form-control" 
                                v-model="editList.name" 
                                ref="listEdit">
                        </div>
                        <div class="col-sm-1 float-right">
                            <a href="#" @click="deleteList(list.id)">
                                <span class="fa fa-trash"></span> 
                            </a>
                        </div>
                        <div class="col-sm-1 float-right">
                            <a href="#" @click="startEditing(index)">
                                <span class="fa fa-pencil"></span> 
                            </a>
                        </div>                        
                    </li>
                </ul>
                <form @submit.prevent="addList">
                    <input type="text" v-model="newList" class="form-control" placeholder="Add new list and hit enter">
                </form>                
            </div>
        </div>
        <div class="row" v-if="renderType=='detail'">
            <div class="col-sm-6 pt-5">
                <h4>{{ shoppingList.name }}</h4>
                <ul class="list-group pb-4">
                    <li v-for="(item,index) in shoppingList.listitems" :key="index" class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1 text-right">{{ index+1 }}</div>
                            <div class="col-sm-10">
                                <h6>{{ item.name }}</h6>
                            </div>
                            <div class="col-sm-1">
                                <a href="#" @click="deleteItem(item.id)">
                                    <span class="fa fa-trash"></span> 
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <form @submit.prevent="addItem">
                    <input type="text" v-model="newItem" class="form-control" placeholder="Add new item and hit enter">
                </form>

            </div>
            <div class="col-sm-4 col-sm-offset-2 pt-5">
                <a href="javascript:void(0)" @click="back">Back to Lists</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ShoppingListIndex',
    data() {
        return {
            renderType: 'list',
            shoppingLists: [],
            shoppingList: {},
            newList: null,
            newItem: null,
            editList: {},
            editListOffset: -1,
        }
    },
    methods: {
        getLists() {
            axios.get('http://localhost/api/shoppinglists')
                .then(response => {
                    console.log(response.data)
                    this.shoppingLists = response.data
                })
        },
        getList(id) {
            console.log('list.id : ' + id)

            axios.get('http://localhost/api/shoppinglists/'+id)
                .then(response => {
                    console.log(response.data)
                    this.shoppingList = response.data
                    this.renderType = 'detail'
                })
        },
        back() {
            this.renderType = 'list'
        },
        addList() {
            let listName = this.newList.charAt(0).toUpperCase()+this.newList.slice(1)

            axios.post('http://localhost/api/shoppinglists/', {'name': listName})
                .then(response => {
                    this.getLists()
                })            

            this.newList = null
        },
        startEditing(index) {
            
            console.log(this.$refs)
            // this.$refs.listEdit.$el.focus()
            this.editListOffset = index
            this.editList = this.shoppingLists[index]
        },
        cancelEdit() {
            this.editListOffset = -1
        },
        updateList() {
            let editListName = this.editList.name.charAt(0).toUpperCase()+this.editList.name.slice(1)

            axios.put('http://localhost/api/shoppinglists/'+this.editList.id, {'name': editListName})
                .then(response => {
                    this.editListOffset = -1
                    this.getLists()
                })
        },
        addItem() {
            let itemName = this.newItem.charAt(0).toUpperCase()+this.newItem.slice(1)

            axios.post('http://localhost/api/shoppinglists/'+this.shoppingList.id+'/listitems/', {'name': itemName})
                .then(response => {
                    this.getList(this.shoppingList.id)
                })            

            this.newItem = null
        },
        deleteList(listId) {
            axios.delete('http://localhost/api/shoppinglists/'+listId)
                .then(response => {
                    this.getLists()
                })                        
        },
        deleteItem(itemId) {
            axios.delete('http://localhost/api/shoppinglists/'+this.shoppingList.id+'/listitems/'+itemId)
                .then(response => {
                    this.getList(this.shoppingList.id)
                })                        
        }
    },
    created() {
        this.getLists()
    },
    mounted() {
        
    }

}
</script>

