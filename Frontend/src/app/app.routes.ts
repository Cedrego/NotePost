import { Routes } from '@angular/router';
import { CreateUserComponent } from './create-user/create-user.component'; // importa el componente
import { HomeComponent } from './home/home.component'; // importa el componente
import { IniciarSesionComponent } from './iniciar-sesion/iniciar-sesion.component';
import { CrearPostComponent } from './crear-post/crear-post.component';
import { CambiarAvatarComponent } from './cambiar-avatar/cambiar-avatar.component';
//import { CambiarPrivacidadComponent } from './cambiar-privacidad/cambiar-privacidad.component';
import { MenuUsuarioComponent } from './menu-usuario/menu-usuario.component';
import { VerUsuarioComponent } from './ver-usuario/ver-usuario.component';
import { FilteredPostListComponent } from './filtered-post-list/filtered-post-list.component';
import { RankingComponent } from './ranking/ranking.component'; // importa el componente de ranking

export const routes: Routes = [
  { path: '',pathMatch: 'full',redirectTo: 'home' },
  { path: 'create-user', component: CreateUserComponent },
  { path: 'home', component: HomeComponent }, // nueva ruta
  { path: 'iniciar-sesion', component: IniciarSesionComponent },
  { path: 'crear-post', component: CrearPostComponent },
  { path: 'cambiar-avatar', component: CambiarAvatarComponent },
 // { path: 'cambiar-privacidad', component: CambiarPrivacidadComponent },  ESTO FUE ELIMINADO
  { path: 'menu-usuario', component: MenuUsuarioComponent }, // nueva ruta para el menú de usuario
  { path: 'ver-usuario', component: VerUsuarioComponent },
  { path: 'filtered-posts', component: FilteredPostListComponent }, // nueva ruta para los posts filtrados
  {path: 'ranking', component: RankingComponent}, // nueva ruta para el ranking
];
