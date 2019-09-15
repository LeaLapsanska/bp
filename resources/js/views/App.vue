<template>
    <div class="page-wrapper" v-if="auth.check()">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="images/icon/logo.png" alt="CoolAdmin">
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
                            <a href="chart.html">
                                <i class="fas fa-chart-bar"></i>Charts
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <router-link :to="{ name: 'home' }">
                    <img src="images/logo.png" alt="Room Managemnet System" />
                </router-link>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <router-link :to="{ name: 'home' }"><i class="fas fa-home"></i>Domov</router-link>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-suitcase"></i>Moje udalosti</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <router-link :to="{ name: 'myevents' }"><i class="fas fa-list-alt"></i>Zoznam</router-link>
                                </li>
                                <li>
                                    <router-link :to="{ name: 'mycalendar' }"><i class="fas fa-calendar"></i>Kalendár</router-link>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <router-link :to="{ name: 'roomsOverview' }"><i class="fas fa-eye"></i>Prehľad miestností</router-link>
                        </li>
                           <template v-if="auth.getUser().isManager == 1">
                               <li class="has-sub">
                                   <a class="js-arrow" href="#">
                                       <i class="fas fa-wrench"></i>Správa systému</a>
                               <ul class="list-unstyled navbar__sub-list js-sub-list">
                                   <template v-if="auth.getUser().isAdmin == 1">
                                       <li>
                                           <router-link :to="{ name: 'users' }"><i class="fas fa-users"></i>Používatelia</router-link>
                                       </li>
                                   </template>
                               <li>
                                   <router-link :to="{ name: 'rooms' }"><i class="fas fa-tags"></i>Miestnosti</router-link>
                               </li>
                               <li>
                                   <router-link :to="{ name: 'parameters' }"><i class="fas fa-plus"></i>Parametre</router-link>
                               </li>
                               <li class="has-sub">
                                   <a class="js-arrow" href="#">
                                       <i class="fas fa-bell"></i>Udalosti</a>
                                   <ul class="list-unstyled navbar__sub-list js-sub-list">
                                       <li>
                                           <router-link :to="{ name: 'events' }"><i class="fas fa-list-alt"></i>Zoznam</router-link>
                                       </li>
                                       <li>
                                           <router-link :to="{ name: 'calendar' }"><i class="fas fa-calendar"></i>Kalendár</router-link>
                                       </li>
                                   </ul>
                               </li>
                               </ul>
                           </li>
                           </template>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action method="POST"></form>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{ auth.getUser().name }}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                    <h5 class="name">
                                                        {{ auth.getUser().name }}
                                                    </h5>
                                                    <span class="email">{{ auth.getUser().email }}</span>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                        <router-link :to="{ name: 'profile' }"><i class="zmdi zmdi-account"></i>Profil</router-link>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="javascript:;" @click="logout()">
                                                    <i class="zmdi zmdi-power"></i>Odhlásiť sa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
					<router-view></router-view>
				</div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>
    </div>
	<div v-else>
		<router-view></router-view>
	</div>
    <!-- <div>
        <h1><b-badge pill variant="light">Room Management System</b-badge></h1>

        <b-nav>
            <b-nav-item><router-link :to="{ name: 'home' }">Home</router-link></b-nav-item>
            <b-nav-item><router-link :to="{ name: 'hello' }">Hello World</router-link></b-nav-item>
            <b-nav-item><router-link :to="{ name: 'login' }">Login</router-link></b-nav-item>
            <b-nav-item><router-link :to="{ name: 'profile' }">Profile</router-link></b-nav-item>
            <b-nav-item><router-link :to="{ name: 'users' }">Zoznam používateľov</router-link></b-nav-item>
        </b-nav>

        <div class="container">
            <router-view></router-view>
        </div>
        <br>
        <b-button block variant="outline-secondary" type="button" @click="logout()" v-if="auth.check()">Odhlásiť sa</b-button>

    </div>-->
</template>
<script>
import auth from "../auth/index.js";
export default {
    data() {
        return {
            auth: auth,
            isAdmin: false,
            isManager: false,
            user: []
        };
    },
    mounted(){
        console.log(auth.getUser())
        // this.admin();
    },
    methods: {
        logout() {
            axios
                .get("logout", {
                    headers: { Authorization: "Bearer " + this.auth.getToken() }
                })
                .then(response => {
                    auth.removeToken();
                    this.$router.push("/login");
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },
        admin() {
           // this.user = auth.getUser();
         //  this.isAdmin = this.user.isAdmin;
         //   console.log(this.user);
        },
    }
};
</script>
