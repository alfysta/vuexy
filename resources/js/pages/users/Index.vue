<script setup>
import { ref, watch } from "vue"
import { Link, router } from "@inertiajs/vue3"
import debounce from "lodash/debounce"
import Layout from "@/layouts/Layout.vue"

const props = defineProps({
    users: Object,
    filters: Object
})

const formatDate = (dateString) => {
    if (!dateString) return "-"
    const date = new Date(dateString)
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    })
}

// reactive state
const search = ref(props.filters.search || "")
const perPage = ref(props.filters.perPage || 10)

// fungsi kirim query
const updateQuery = () => {
    router.get(
        "/users",
        { search: search.value, perPage: perPage.value },
        { preserveState: true, replace: true }
    )
}

// debounce search biar ga spam request
watch(
    search,
    debounce(() => {
        updateQuery()
    }, 500)
)

// perPage langsung trigger
watch(perPage, () => {
    updateQuery()
})

// Helper inisial avatar
const getInitials = (name) => {
    if (!name) return "?"
    return name.split(" ").map(n => n[0]).join("").toUpperCase()
}
</script>

<template>
    <Layout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h2 class="mb-3">Daftar Users</h2>

            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Session</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">21,459</h4>
                                        <span class="text-success">(+29%)</span>
                                    </div>
                                    <span>Total Users</span>
                                </div>
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ti ti-user ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Paid Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">4,567</h4>
                                        <span class="text-success">(+18%)</span>
                                    </div>
                                    <span>Last week analytics </span>
                                </div>
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ti ti-user-plus ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Active Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">19,860</h4>
                                        <span class="text-danger">(-14%)</span>
                                    </div>
                                    <span>Last week analytics</span>
                                </div>
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ti ti-user-check ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Pending Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">237</h4>
                                        <span class="text-success">(+42%)</span>
                                    </div>
                                    <span>Last week analytics</span>
                                </div>
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ti ti-user-exclamation ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & PerPage -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input v-model="search" type="text" class="form-control" placeholder="Cari nama atau email..." />
                </div>
                <div class="col-md-3">
                    <select v-model="perPage" class="form-select">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in props.users.data" :key="user.id">
                            <td>{{ index + 1 + (props.users.current_page - 1) * props.users.per_page }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2"
                                        style="width:40px; height:40px; font-size:14px; font-weight:600;">
                                        <img v-if="user.avatar" :src="user.avatar" :alt="user.name"
                                            class="rounded-circle" style="width:40px; height:40px; object-fit:cover;" />
                                        <span v-else>{{ getInitials(user.name) }}</span>
                                    </div>
                                    <div class="align-items-center">
                                        <span class="font-semibold">{{ user.name }}</span><br>
                                        <span class="text-sm">{{ user.email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>{{ user.email }}</td>
                            <td>{{ formatDate(user.created_at) }}</td>
                            <td>
                                <Link :href="`/users/${user.id}/edit`" class="btn btn-sm btn-primary">
                                Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="props.users.data.length === 0">
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>
                    Menampilkan {{ props.users.from }} - {{ props.users.to }} dari {{ props.users.total }} data
                </span>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: !props.users.prev_page_url }">
                            <Link class="page-link" :href="props.users.prev_page_url ?? '#'">Prev</Link>
                        </li>

                        <li v-for="page in props.users.last_page" :key="page" class="page-item"
                            :class="{ active: page === props.users.current_page }">
                            <Link class="page-link" :href="`/users?page=${page}&search=${search}&perPage=${perPage}`">
                            {{ page }}
                            </Link>
                        </li>

                        <li class="page-item" :class="{ disabled: !props.users.next_page_url }">
                            <Link class="page-link" :href="props.users.next_page_url ?? '#'">Next</Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </Layout>
</template>
