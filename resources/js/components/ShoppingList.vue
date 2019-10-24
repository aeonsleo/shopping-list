<template>
  <div class="container">
    <div class="row" v-if="renderType=='list'">
      <div class="col-12 pt-5">
        <h4 class="pb-1">Shopping Lists</h4>
        <form @submit.prevent="addList" class="pb-4">
          <input
            type="text"
            v-model="newList"
            class="form-control"
            placeholder="Add new list and hit enter"
          />
        </form>
        <ul class="list-group pb-4">
          <li v-for="(list,index) in shoppingLists" :key="list.id" class="list-group-item">
            <div class="col-8 float-left">
              <a
                v-if="editListOffset!=index"
                href="javascript:void(0)"
                v-on:click="getList(list.id)"
              >{{ list.name }}</a>
              <div id="myId" ref="myId"></div>
              <input
                type="text"
                v-show="editListOffset==index"
                ref="foo"
                @keydown.enter="updateList"
                v-on:blur="cancelEdit(index)"
                class="form-control"
                v-model="editList.name"
              />
            </div>
            <div class="col-1 float-right text-right">
              <a href="#" @click="deleteList(list.id)">
                <span class="fa fa-trash fa-lg"></span>
              </a>
            </div>
            <div class="col-2 float-right text-right">
              <a href="#" @click="startEditing(index)">
                <span class="fa fa-pencil fa-lg"></span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="row" v-if="renderType=='detail'">
      <div class="col-12 pt-5">
        <a href="javascript:void(0)" @click="back">&lt; Back to Lists</a>
      </div>
      <div class="col-12 pt-5">
        <div class="row">
          <div class="col-8">
            <h4>{{ shoppingList.name }}</h4>
          </div>
          <div class="col-4">{{ shoppingList.created_at | shortDate }}</div>
        </div>
        <form @submit.prevent="addItem" class="pb-4">
          <input
            type="text"
            v-model="newItem"
            class="form-control"
            placeholder="Add new item and hit enter"
          />
        </form>
        <ul class="list-group pb-4">
          <li v-for="(item,index) in shoppingList.listitems" :key="index" class="list-group-item">
            <div class="row">
              <div class="col-10">
                <h6>{{ item.name }}</h6>
              </div>
              <div class="col-2 text-right">
                <a href="#" @click="deleteItem(item.id)">
                  <span class="fa fa-trash fa-lg red"></span>
                </a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ShoppingListIndex",
  data() {
    return {
      renderType: "list",
      shoppingLists: [],
      shoppingList: {},
      newList: null,
      newItem: null,
      editListUneditedName: null,
      editList: {},
      editListOffset: -1
    };
  },
  props: {
    appUrl: null
  },
  filters: {
    shortDate: function(value) {
      if (!value) return "";

      value = value.toString();

      return moment(value).format("MMMM Do YYYY");
    }
  },
  methods: {
    getLists() {
      axios
        .get("/api/shoppinglists")
        .then(response => {
          console.log(response.data);
          this.shoppingLists = response.data;
        })
        .catch(error => {
          console.log("lists", error);
        });
    },
    getList(id) {
      console.log("list.id : " + id);

      axios
        .get("/api/shoppinglists/" + id)
        .then(response => {
          console.log(response.data);
          this.shoppingList = response.data;
          this.renderType = "detail";
        })
        .catch(error => {
          console.log("hello", error);
        });
    },
    back() {
      this.renderType = "list";
    },
    addList() {
      let listName =
        this.newList.charAt(0).toUpperCase() + this.newList.slice(1);

      axios
        .post("/api/shoppinglists", { name: listName })
        .then(response => {
          console.log(response.data);
          this.getLists();
        })
        .catch(error => {
          console.log(error);
        });

      this.newList = null;
    },
    startEditing(index) {
      // this.$refs.listEdit.$el.focus()
      this.editListOffset = index;
      this.editList = this.shoppingLists[index];
      this.editListUneditedName = this.editList.name;

      console.log(this.$refs);
      console.log(this.$refs.foo[index]);

      this.$nextTick(() => {
        this.$refs.foo[index].focus();
      });
    },
    cancelEdit() {
      this.editListOffset = -1;
      this.editList.name = this.editListUneditedName;
    },
    updateList() {
      let editListName =
        this.editList.name.charAt(0).toUpperCase() +
        this.editList.name.slice(1);

      axios
        .put("/api/shoppinglists/" + this.editList.id, { name: editListName })
        .then(response => {
          this.editListOffset = -1;
          this.getLists();
        })
        .catch(error => {
          console.log(error);
        });
    },
    addItem() {
      let itemName =
        this.newItem.charAt(0).toUpperCase() + this.newItem.slice(1);

      axios
        .post("/api/shoppinglists/" + this.shoppingList.id + "/listitems", {
          name: itemName
        })
        .then(response => {
          this.getList(this.shoppingList.id);
        })
        .catch(error => {
          console.log(error);
        });

      this.newItem = null;
    },
    deleteList(listId) {
      if (confirm("Delete List?")) {
        axios
          .delete("/api/shoppinglists/" + listId)
          .then(response => {
            this.getLists();
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    deleteItem(itemId) {
      if (confirm("Delete item?")) {
        axios
          .delete(
            "/api/shoppinglists/" +
              this.shoppingList.id +
              "/listitems/" +
              itemId
          )
          .then(response => {
            this.getList(this.shoppingList.id);
          })
          .catch(error => {
            console.log(error);
          });
      }
    }
  },
  created() {
    this.getLists();
  },
  mounted() {}
};
</script>

<style>
ul.list-group {
  max-height: 75%;
  overflow-y: scroll;
}
ul.list-group li a,
ul.list-group li h6 {
  font-size: 1.3rem !important;
  color: rgb(95, 95, 95);
}
.fa-trash {
  color: lightcoral;
}
.fa-pencil {
  color: lightseagreen;
}
</style>

