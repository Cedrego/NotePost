import { Component, inject } from '@angular/core';
import {
  FormBuilder, FormGroup, Validators,
  ReactiveFormsModule, FormsModule
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { OnInit } from '@angular/core';
import { SessionService } from '../services/session.service';
@Component({
  selector: 'app-cambiar-avatar',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './cambiar-avatar.component.html',
  styleUrl: './cambiar-avatar.component.scss'
})
export class CambiarAvatarComponent implements OnInit {
  sessionService = inject(SessionService);
  avatarForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

   avatars: { id: number, rutaImagen: string }[] = [];

  constructor() {
    this.avatarForm = this.fb.group({
      idAvatar: ['1']
    });
  }

  ngOnInit(): void {
    this.userService.getTodosAvatares().subscribe({
      next: (data: any) => {
        // Si tu backend devuelve { Solicitudes: [...] }
        this.avatars = data.Solicitudes ?? [];
      },
      error: (error: any) => {
        this.alertMessage = 'Error al cargar avatares';
        this.showAlert = true;
      }
    });
  }
  onSubmit(): void {
  if (this.avatarForm.invalid) {
    this.alertMessage = 'Formulario inválido';
    this.showAlert = true;
    return;
  }

  const formData = {
    ...this.avatarForm.value,
    usuario: this.sessionService.getUsuario()
  };
  this.userService.enviarAvatar(formData).subscribe({
    next: (respuesta: any) => {
      this.alertMessage = '¡Enviado correctamente!';
      this.showAlert = true;
      console.log('Respuesta del servidor:', respuesta);
      if (respuesta.success) {
      setTimeout(() => window.location.reload(), 1000); // Espera 1 segundo y recarga
    }
    },
    error: (error: any) => {
      this.alertMessage = 'Error al enviar';
      this.showAlert = true;
      console.error('Error:', error);
    }
  });
}

  // Getters para los campos
  get idAvatar() { return this.avatarForm.get('idAvatar'); }
}
