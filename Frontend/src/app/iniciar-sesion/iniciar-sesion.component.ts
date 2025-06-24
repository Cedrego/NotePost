import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule, AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { Router } from '@angular/router';// <-- Importando Router para redirección a Menu-Usuario despues de login exitoso
@Component({
  selector: 'app-iniciar-sesion',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './iniciar-sesion.component.html',
  styleUrl: './iniciar-sesion.component.scss'
})
export class IniciarSesionComponent implements OnInit{
  sesionForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);
  sessionService = inject(SessionService); // <-- Agregadondo servicio de sesión
  router = inject(Router); // <-- Inyectando Router para redirección

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.sesionForm = this.fb.group({
      nick: ['', [Validators.required]],
      pass: ['', [Validators.required]],
      repeatpass: ['', Validators.required]
     });
  }

  ngOnInit(): void {
  }


  onSubmit(): void {
    if (this.sesionForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.sesionForm.value;

    this.userService.enviarIniciarSesion(formData).subscribe({
      next: (respuesta) => {
        this.sessionService.setUsuario(formData.nick); // <-- Guardar usuario en sesión
        this.alertMessage = '¡Enviado correctamente!';
        this.showAlert = true;
        console.log('Respuesta del servidor:', respuesta);
        setTimeout(() => {
          this.router.navigate(['/home']); // Redirigir a la página de inicio después de iniciar sesión
        }, 1000); // Espera 1 segundo antes de redirigir (opcional)
      },
       error: (error: any) => {
        if (error.status === 401 && error.error && error.error.error) {
          this.alertMessage = error.error.error; // Mensaje del backend
        } else {
          this.alertMessage = 'Error al enviar';
        }
        this.showAlert = true;
        console.error('Error:', error);
      }
    });
  }
  // Getters para los campos
  get nick() { return this.sesionForm.get('nick'); }
  get pass() { return this.sesionForm.get('pass'); }
  get repeatpass() { return this.sesionForm.get('repeatpass'); }


}
