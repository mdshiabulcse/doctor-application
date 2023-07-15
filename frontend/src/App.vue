<script setup>
import {HeaderPart,NavBar,CartSidebar,MobileMenu,LoginModal,NewsLetter,FooterPart} from "@/components";


</script>
<template>
  <div>
    <div class="backdrop"></div>
    <a class="backtop fas fa-arrow-up" href="#"></a>
    <!--    Header Part-->
<!--    <HeaderPart/>-->
    <!--    Navbar part-->
<!--    <NavBar/>-->

<!--    &lt;!&ndash;    Cart Sidebar Part&ndash;&gt;-->
<!--    <CartSidebar/>-->
<!--    &lt;!&ndash;    Mobile menu Part&ndash;&gt;-->
<!--    <MobileMenu/>-->

<!--    &lt;!&ndash;Login modal Part&ndash;&gt;-->

<!--    <LoginModal/>-->

    <!--router view part area start-->
    <router-view></router-view>
    <!--router view part area end-->


<!--    news letter Part-->
<!--    <NewsLetter/>-->

<!--    <FooterPart/>-->
  </div>

</template>
