<template>
	<div class="card">
    <div class="card-body" v-if="loading">
      <spinner></spinner>
    </div>
		<div class="card-body" v-else>
      <div v-for="(item, index) in documents" :key="index">
        <document-list-item
        :title="item['title']"
        :coverDate="item['published_at']"
        :hIndex="getHIndex(item)"
        :subType="item.subtype_description"
        :authors="item.authors"
        :publicationName="item.publication_name"
        ></document-list-item>
        <hr v-if="index+1 != documents.length">
      </div>
		</div>
    <div class="card-footer">
      <paginator v-if="!loading"
      :meta="meta"
      :links="links"
      :page.sync="page"></paginator>
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
      links: {},
      meta: {},
    	loading: true,
    }
  },
  methods: {
  	async fetchDocuments() {
  		let response = await axios.get('/api/documents', {
        params: this.query,
      })
  		this.documents = response.data.data
      this.links = response.data.links
      this.meta = response.data.meta
  	},
    getHIndex(item) {
      if (item.scimago && item.scimago.h_index != "-") {
        return item.scimago['h_index']
      } else {
        return "Not Available"
      }
    }
  },
  computed: {
    query: function () {
      return this.$store.getters['query/getQuery']
    },
    page: {
      get: function () {
        return this.query.page
      },
      set: function (value) {
        this.query.page = value
      }
    },
  },
  watch: {
    query: {
      async handler(newVal) {
          this.loading = true
          await this.fetchDocuments()
          this.loading = false
      },
      deep: true,
    }
  },
  async mounted() {
  	await this.fetchDocuments();
    this.loading = false
  },
  components: {
    DocumentListItem,
  }
}
</script>

<style lang="css" scoped>
</style>