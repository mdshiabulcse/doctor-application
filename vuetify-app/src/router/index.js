// Composables
import {createRouter, createWebHistory} from 'vue-router'
import DashboardHome from "@/views/dashboard/DashboardHome.vue";
// import UserProfile from "@/views/dashboard/UserProfile.vue";
import UserLogin from "@/views/auth/Login.vue";
import DefaultPage from "@/layouts/default/Default.vue";
import {useAuth} from "@/store/auth.js";
import {DoctorList,UserInfo,PatientSourceInfo} from "@/views/dashboard/Index";
import {PatientData,PatientCreate,PatientDetails,PatientAppointment, AppointmentData,PatientPrescription} from "@/views/dashboard/patients/Index";
import {PatientExaminationInvoice,PatientConsultationInvoice,PatientInvoiceList} from "@/views/dashboard/invoice/Index";
import {OperationList} from "@/views/dashboard/ot/Index";

const routes = [
  {
    path: '/', name: 'user.login', component: UserLogin, meta:{title:"User Login", guest:true},
  },
  {
    path: '/dashboard',
    component: DefaultPage,
    children: [
      {
        path: '/dashboard', name: 'user.dashboard', component: DashboardHome, meta:{title:"User Dashboard", requiresAuth:true},

      },
      // -----------Adminstration Start-----------
      {
        path: '/user-list', name: 'user.list', component: UserInfo, meta:{title:"User Info", requiresAuth:true},
      },
      {
        path: '/patient-source', name: 'patient.source', component: PatientSourceInfo, meta:{title:"Patient Source", requiresAuth:true},
      },
      {
        path: '/doctor-list', name: 'doctor.list', component: DoctorList, meta:{title:"Doctor List", requiresAuth:true},
      },
      // -----------Adminstration End-----------
      // -----------Patients  Info Start-----------
      {
        path: '/patient-info', name: 'patient.info', component: PatientData, meta:{title:"Patient Info", requiresAuth:true},
      },
      {
        path: '/patient-create', name: 'patient.create', component: PatientCreate, meta:{title:"Patient Create", requiresAuth:true},
      },
      {
        path: '/patient-create/:patientId', name: 'patient.create.:patientId', component: PatientCreate, meta:{title:"Patient Edit", requiresAuth:true},
      },
      {
        path: '/patient-details/:patientId', name: 'patient.details', component: PatientDetails, meta:{title:"Patient Details", requiresAuth:true},
      },
      {
        path: '/patient-appointment/:patientId', name: 'patient.appointment', component: PatientAppointment, meta:{title:"Patient Appointment", requiresAuth:true},
      },
      {
        path: '/patient-appointment-data', name: 'appointment.data', component: AppointmentData, meta:{title:"Patient Appointment Data", requiresAuth:true},
      },
      {
        path: '/patient-prescription/:app_id/:pid/:prescriptionId', name: 'patient.prescription ', component: PatientPrescription, meta:{title:"Patient Precription", requiresAuth:true},
      },
      // -----------Patients  Info End-----------
      /* -----------Invoice  Info Start-----------*/
      {
        path: '/patient-examination-invoice/:patientId', name: 'patient.examination', component: PatientExaminationInvoice, meta:{title:"Patient Examination Invoice", requiresAuth:true},
      },
      {
        path: '/patient-consultation-invoice/:id/:patientId', name: 'patient.consultation', component: PatientConsultationInvoice, meta:{title:"Patient Consultation Invoice", requiresAuth:true},
      },
      {
        path: '/invoice-list', name: 'invoice.list', component: PatientInvoiceList, meta:{title:"Patient Invoice List", requiresAuth:true},
      },
      /* -----------Invoice  Info End----------- */

      /*   Start:: Operation Route  */
      {
        path: '/operation-list', name: 'operation.list', component: OperationList, meta:{title:"Operation List", requiresAuth:true},
      },
      /*   End:: Operation Route  */
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

const DEFAULT_TITLE= "404";

router.beforeEach((to,from,next) => {
  document.title=to.meta.title||DEFAULT_TITLE;
  const loggedIn = useAuth();
  if (to.matched.some((record)=>record.meta.requiresAuth)){
    if(!loggedIn.user.meta){
      next({name:"user.login"});
    }else{
      next()
    }
  }else if (to.matched.some((record)=>record.meta.guest)){
    if(loggedIn.user.meta){
      next({name:"user.dashboard"});
    }else{
      next()
    }
  }
  else{
    next();
  }

});
export default router
