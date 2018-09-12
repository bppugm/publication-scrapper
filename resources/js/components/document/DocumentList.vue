<template>
	<div class="card">
		<div class="card-body" v-if="!loading">
      <div v-for="(item, index) in documents" :key="index">
        <document-list-item
        :title="item['title']"
        :coverDate="item['published_at']"
        :HIndex="getHIndex(item)"
        ></document-list-item>
        <hr v-if="index+1 != documents.length">
      </div>
		</div>
	</div>
</template>

<script>
import DocumentListItem from './DocumentListItem';

export default {

  name: 'DocumentList',

  data () {
    return {
    	documents: [],
    	loading: true,
    }
  },
  methods: {
  	async fetchDocuments() {
  		let response = await axios.get('/api/documents')
  		this.documents = response.data.data
      this.loading = false
  	},
    getHIndex(item) {
      if (item.scimago) {
        return item.scimago['h_index']
      } else {
        return "Not Available"
      }
    }
  },
  mounted() {
  	this.fetchDocuments();
  },
  components: {
    DocumentListItem,
  }
}
</script>

<style lang="css" scoped>
</style>