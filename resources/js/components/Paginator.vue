<template>
    <nav v-if="meta.last_page !== 1" aria-label="Page navigation example" class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled : links.prev == null}">
          <button class="page-link" @click="changePage(prevPage)" tabindex="-1">Previous</button>
        </li>
        <li 
        v-for="page in renderedPages" 
        class="page-item" 
        :class="{ active: meta.current_page == page }"
        >
          <button class="page-link" @click="changePage(page)">{{ page }}</button>
        </li>
        <li class="page-item" :class="{ disabled : links.next == null}">
          <button class="page-link" @click="changePage(nextPage)">Next</button>
        </li>
      </ul>
    </nav>
</template>

<script>
export default {

  name: 'Paginator',
  props: {
    meta: Object,
    links: Object,
  },
  data () {
    return {

    }
  },
  methods: {
    changePage(page) {
      this.$emit('update:page', page)
    },
    getRenderedPages(first, last) {
      return this.allPages.slice(first, last)
    },
    getBasePage(currentPage) {
      if (currentPage%5 == 0) {
        return ((currentPage/5)-1)*5
      }

      var base = Math.floor(this.meta.current_page/5)*5
      return base
    }
  },
  computed: {
    allPages: function() {
      var pages = [...Array(this.lastPage+1).keys()]

      pages.shift()

      return pages
    },
    renderedPages: function () {
      var base = this.getBasePage(this.meta.current_page);
      
      return this.getRenderedPages(base, base+5)
    },
    lastPage: function () {
      if (this.meta.last_page) {
        return this.meta.last_page
      } else {
        return 0
      }
    },
    nextPage: function () {
      return this.meta.current_page+1
    },
    prevPage: function () {
      return this.meta.current_page-1
    }
  }
}
</script>

<style lang="css" scoped>
</style>
