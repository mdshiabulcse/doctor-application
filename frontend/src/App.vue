<script setup>
import {HeaderPart,NavBar,CartSidebar,MobileMenu,LoginModal,NewsLetter,FooterPart} from "@/components";


</script>
<template>
  <div>
    <div class="backdrop"></div>
    <a class="backtop fas fa-arrow-up" href="#"></a>
    <!--    Header Part-->
    <HeaderPart/>
    <!--    Navbar part-->
    <NavBar/>

    <!--    Cart Sidebar Part-->
    <CartSidebar/>
    <!--    Mobile menu Part-->
    <MobileMenu/>

    <!--Login modal Part-->

    <LoginModal/>

    <!--router view part area start-->
    <router-view></router-view>
    <!--router view part area end-->


<!--    news letter Part-->
    <NewsLetter/>

    <FooterPart/>
  </div>
</template>