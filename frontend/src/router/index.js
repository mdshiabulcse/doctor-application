import {createRouter, createWebHistory} from 'vue-router';
import NProgress from 'nprogress';
import {Index, Shop, SingleProduct,Checkout} from "@/views/pages/index.js";
import {Seller,SellerStore,SellerApply} from "@/views/pages/seller/index.js";
import {UserLogin, UserRegister} from "@/views/auth/index.js";
import {MyOrderList, MyProfile, MyWishlist} from "@/views/user/index.js";
import {useAuth} from "@/stores";



const routes = [
    {path: '/', name: '/', component: Index, meta: { title: 'Home' }},
    {path: '/auth/login', name: "user.login", component: UserLogin, meta: { title: 'User Login' ,guest:true}},
    {path: '/auth/register', name: "user.register", component: UserRegister, meta: { title: 'User Register',guest: true }},

    {path: '/user-orders', name: "user.orders", component: MyOrderList, meta: { title: 'User Orders',requiresAuth: true }},
    {path: '/user-profile', name: "user.profile", component: MyProfile, meta: { title: 'User Profile' ,requiresAuth: true}},
    {path: '/user-wishlist', name: "user.wishlist", component: MyWishlist, meta: { title: 'User Wishlist' ,requiresAuth: true}},

   //user route end=====

    {path: '/shop', name: "shop.page", component: Shop, meta: { title: 'Shop' }},
    {path: '/product-details', name: "product.details", component: SingleProduct, meta: { title: 'Product Details' }},
    {path: '/checkout-page', name: "checkout.page", component: Checkout, meta: { title: 'Checkout ' }},
    {path: '/seller-list', name: "seller.page", component: Seller, meta: { title: 'Seller' }},
    {path: '/seller-store', name: "seller.store", component: SellerStore, meta: { title: 'Seller Store' }},
    {path: '/seller-apply', name: "seller.apply", component: SellerApply, meta: { title: 'Seller Apply' }},

];
const router = createRouter({
    history: createWebHistory(import.meta.BASE_URL),
    routes,
})

const DEFAULT_TITLE = '404';
router.beforeEach((to,from,next) => {
    document.title = to.meta.title || DEFAULT_TITLE;
    NProgress.start();
    const loggedIn =useAuth();
    if (to.matched.some((record)=>record.meta.requiresAuth)){
        if (!loggedIn.user.meta){
            next({name:"user.login"});
        }else {
            next();
        }
    }else if (to.matched.some((record)=>record.meta.guest)){
        if (loggedIn.user.meta){
            next({name:"user.profile"});
        }else {
            next();
        }
    }
    else {
        next();
    }


});
router.afterEach(() => {
    NProgress.done();
});
export default router;