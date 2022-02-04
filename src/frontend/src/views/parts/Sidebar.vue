<template>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 ps bg-gradient-dark">
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul v-if="status.authed && status.role === roles.USER" class="navbar-nav container">
                <li v-for="(location, index) in locationsAuthorized" :key="index" class="nav-item" >
                    <router-link class="nav-link" exact-active-class="active" :active-class="location.link === '/'? '' : 'active'" :to="location.link">
                        {{ location.title }}
                    </router-link>
                </li>
            </ul>
            <ul v-else-if="status.authed && status.role === roles.ADMIN" class="navbar-nav container">
                <li v-for="(location, index) in locationsAuthorizedAdmin" :key="index" class="nav-item" >
                    <router-link class="nav-link" exact-active-class="active" :active-class="location.link === '/'? '' : 'active'" :to="location.link">
                        {{ location.title }}
                    </router-link>
                </li>
            </ul>
            <ul v-else  class="navbar-nav container">
                <li v-for="(location, index) in locationsGuest" :key="index" class="nav-item" >
                    <router-link class="nav-link" exact-active-class="active" :active-class="location.link === '/'? '' : 'active'" :to="location.link">
                        {{ location.title }}
                    </router-link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {RawLocation} from "vue-router/types/router";
import {User, UserRole} from "@/components/backend-api/entities/users";

@Component
export default class Sidebar extends Vue
{
    status = {
        authed: false,
        role: -1,
    };

    roles = UserRole;

    locationsAuthorized: {title: string, link: RawLocation}[] = [
        {title: 'Wallet', link: {name: 'wallet'}},
    ];

    locationsAuthorizedAdmin: {title: string, link: RawLocation}[] = [
        {title: 'Wallet', link: {name: 'wallet'}},
        {title: 'Wallet Add', link: {name: 'wallet-add'}},
    ];

    locationsGuest: {title: string, link: RawLocation}[] = [
        {title: 'Login', link: '/'},
    ];



    beforeMount(): void {
        this.status.authed = !!this.$api.user;

        this.$eventBus.$on('user', (user: User|null) => {
            this.status.authed = !!user;
            this.status.role = user ? user.role : -1;
        });
    }
}
</script>
