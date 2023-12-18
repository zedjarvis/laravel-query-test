<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { format } from 'date-fns';
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { Skeletor, useSkeletor } from 'vue-skeletor';
import 'vue-skeletor/dist/vue-skeletor.css';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

interface Article {
    id: number
    title: string
    description: string
    content: string
    published: string
}

const skeletor = useSkeletor()
// console.log(props.articles);

const isLoading = ref(false)
const allArticles = ref<Article[]>([])
const prevPage = ref()
const currentPage = ref(1)
const articleCount = ref()
const pagination = 20
const search_query = ref('')

const canLoadMore = computed(() => currentPage.value <= articleCount.value / pagination)

const onSearch = async () => {
    await axios.get(`/api/articles/search?query=${search_query.value}`)
        .then((res) => console.log(res.data))
        .catch((err) => console.log(err))
}

const getArticles = async () => {
    console.log('loading more', canLoadMore.value)
    if (articleCount.value !== undefined && !canLoadMore.value) {
        alert('no more posts')
        return
    }
    isLoading.value = true
    prevPage.value = currentPage.value
    await axios.get(`/api/articles?page=${currentPage.value}`)
        .then((res) => {
            const { articles, article_count } = res.data
            allArticles.value = [...allArticles.value, ...articles]
            articleCount.value = article_count
            currentPage.value += 1
            // isLoading.value = false
        })
        .catch((err) => {
            currentPage.value = prevPage.value
            console.log(err)
        })
}

//observe the intersection to trigger loading of additional posts
const loadMoreIntersect = ref()

onBeforeMount(async () => {
    await getArticles()

})

onMounted(() => {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => entry.isIntersecting && getArticles(), {
        rootMargin: "-150px 0px 0px 0px"
    }));
    observer.observe(loadMoreIntersect.value)
})
</script>

<template>
    <Head title="News" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between w-full">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">News</h2>
                <form class="w-96" @submit.prevent="onSearch">
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search" v-model="search_query"
                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Mockups, Logos..." required>
                        <button type="submit"
                            class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                    </div>
                </form>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="allArticles.length" class="grid grid-cols-3 gap-4 min-h-screen">
                    <div v-for="(article, index) in allArticles" :key="article.id"
                        class="bg-white w-full h-56 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="grid grid-cols-2">
                            <div>
                                <img :src="`https://loremflickr.com/200/300?random=${index}`" alt="">
                            </div>
                            <div class="p-2">
                                <h1 class="uppercase text-base font-bold mb-1 text-blue-700">{{ article.title }}</h1>
                                <span class="text-xs text-gray-700">{{ format(new Date(article.published),
                                    'PPPPpppp') }}</span>
                                <p>{{ article.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="isLoading" class="grid grid-cols-3 gap-4 min-h-screen">
                    <Skeletor v-for="i in 20" height="224" />
                </div>

                <span ref="loadMoreIntersect" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
