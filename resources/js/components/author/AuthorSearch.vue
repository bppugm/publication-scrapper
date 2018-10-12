<template>
	<div>
		<div v-if="loading" class="card card-body">
			<skeleton>
				<div v-for="item in [0, 1, 2]" class="post">
					<div class="line full"></div>
				</div>
			</skeleton>
		</div>
		<div v-if="emptyResults">
			<h4 class="text-muted">Sorry, we can't find authors you are looking for. Please try with different keywords.</h4>
		</div>
		<div class="card card-body" v-if="!loading && !emptyResults">
			<table class="table">
				<thead>
					<tr>
						<th>Author name</th>
						<th>Scopus ID</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="author in authors" :key="author.auth_id">
						<td>
							<h5><a :href="url('/author/'+getAuthorId(author))">{{ getAuthorName(author) }}</a></h5>
						</td>
						<td>
							{{ getAuthorId(author) }}
						</td>
					</tr>
				</tbody>
			</table>
<!-- 				<div v-for="(author, index) in authors" :key="author.auth_id">
				<author-list-item
				:name="author.authname"
				:auth-id="author.auth_id"
				></author-list-item>
				<hr v-if="authors.length != index+1">
			</div> -->
		</div>
	</div>
</template>

<script>
import AuthorListItem from './AuthorListItem';

export default {

  name: 'AuthorSearch',
  props: {
  	query: Object,
  },
  data () {
    return {
    	loading: true,
    	authors: [],
    }
  },
  methods: {
  	async fetchAuthors() {
  		let response = await axios.get('/api/authors', {
  			params: this.query
  		})

  		this.authors = response.data.data.entry
  		this.loading = false
  	},
  	getAuthorId(author) {
  		let authorId = author['dc:identifier'].replace('AUTHOR_ID:', '')
  		return authorId
  	},
  	getAuthorName(author) {
  		let authorName = author['preferred-name']

  		return authorName['given-name']+' '+authorName['surname']
  	}
  },
  computed: {
  	emptyResults: function () {
  		if (this.loading == false && this.authors.length == 0) {
  			return true	
  		} else {
  			return false
  		}
  	}
  },
  components: {
  	AuthorListItem,
  },
  mounted() {
  	this.fetchAuthors()
  }
}
</script>

<style lang="css" scoped>
</style>