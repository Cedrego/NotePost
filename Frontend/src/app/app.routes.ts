import { Routes } from '@angular/router';
import { CreateUserComponent } from './create-user/create-user.component'; // importa el componente
import { HomeComponent } from './home/home.component'; // importa el componente
import { IniciarSesionComponent } from './iniciar-sesion/iniciar-sesion.component';
import { CrearPostComponent } from './crear-post/crear-post.component';
import { CambiarAvatarComponent } from './cambiar-avatar/cambiar-avatar.component';
import { CambiarPrivacidadComponent } from './cambiar-privacidad/cambiar-privacidad.component';
import { MenuUsuarioComponent } from './menu-usuario/menu-usuario.component';

export const routes: Routes = [
  { path: '',pathMatch: 'full',redirectTo: 'home' },
  { path: 'create-user', component: CreateUserComponent },
  { path: 'home', component: HomeComponent }, // nueva ruta
  { path: 'iniciar-sesion', component: IniciarSesionComponent },
  { path: 'crear-post', component: CrearPostComponent },
  { path: 'cambiar-avatar', component: CambiarAvatarComponent },
  { path: 'cambiar-privacidad', component: CambiarPrivacidadComponent },
  { path: 'menu-usuario', component: MenuUsuarioComponent } // nueva ruta para el menú de usuario

];
