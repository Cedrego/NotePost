import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule, AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service'; // <-- importando servicio de sesión
import { Router } from '@angular/router'; // <-- Importando Router para redirección a Menu-Usuario despues de login exitoso
import { RecordatorioService } from '../services/recordatorio.service'; // <-- Importando servicio de recordatorios

@Component({
  selector: 'app-iniciar-sesion',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './iniciar-sesion.component.html',
  styleUrl: './iniciar-sesion.component.scss'
})
export class IniciarSesionComponent implements OnInit {
  sesionForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);
  sessionService = inject(SessionService); // <-- Agregadondo servicio de sesión
  router = inject(Router); // <-- Inyectando Router para redirección
  recordatorioService = inject(RecordatorioService); // <-- Inyectando servicio de recordatorios

  showAlert = false;
  alertMessage = '';
  id = 10;
  esRecordatorio = false;
  constructor() {
    this.sesionForm = this.fb.group({
      nick: ['', [Validators.required]],
      pass: ['', [Validators.required]],
      repeatpass: ['', Validators.required]
    });
  }

  ngOnInit(): void {}

  onSubmit(): void {
    if (this.sesionForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.sesionForm.value;

    this.userService.enviarIniciarSesion(formData).subscribe({
    next: (respuesta) => {
      this.sessionService.setUsuario(formData.nick);

      // Obtener recordatorios pendientes
      this.recordatorioService.obtenerRecordatoriosPorUsuario(formData.nick).subscribe(
        (recordatorios) => {
          if (recordatorios.length > 0) {
            const recordatoriosPendientes = recordatorios
              .map(r => `- ${r.contenido} (${r.fechaRecordatorio})`)
              .join('\n');
            this.esRecordatorio = true;
            this.alertMessage = `Recordatorios pendientes:\n${recordatoriosPendientes}`;
          } else {
            this.esRecordatorio = true;
            this.alertMessage = '¡Enviado correctamente! No hay recordatorios pendientes.';
          }

          this.showAlert = true;
        },
        (error) => {
          console.error('Error al obtener los recordatorios:', error);
        }
      );
    },
    error: (error: any) => {
      if (error.status === 401 && error.error?.error) {
        this.alertMessage = error.error.error;
      } else {
        this.alertMessage = 'Error al enviar';
      }
      this.showAlert = true;
      console.error('Error:', error);
    }
  });
  }
  aceptarAlerta(): void {
    this.showAlert = false;
    this.router.navigate(['/home']);
  }
  cerrarAlerta(): void {
    this.showAlert = false;
  }
  // Getters para los campos
  get nick() { return this.sesionForm.get('nick'); }
  get pass() { return this.sesionForm.get('pass'); }
  get repeatpass() { return this.sesionForm.get('repeatpass'); }
}