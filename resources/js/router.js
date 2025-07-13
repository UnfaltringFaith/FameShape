import { createRouter, createWebHistory } from 'vue-router'
import PostsPage from './components/Posts/PostsPage.vue'
import PostPage from './components/Posts/PostPage.vue'
import ExampleComponentCopy from './components/ExampleComponent copy.vue'
import CreatePost from './components/Posts/CreatePost.vue'
import Login from './components/login.vue'
import MuscleGroups from './components/Knowledge_base/MuscleGroups.vue'

const routes = [
    { path: '/', component: ExampleComponentCopy },
    { path: '/posts', component: PostsPage },
    { path: '/posts/:id', component: PostPage },
    { path: '/login', component: Login },
    { path: '/posts/create', component: CreatePost },
    { path: '/knowledge_base/muscle_groups', component: MuscleGroups }
]
/*  */
const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router