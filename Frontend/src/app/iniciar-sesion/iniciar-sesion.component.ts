import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule, AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-iniciar-sesion',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './iniciar-sesion.component.html',
  styleUrl: './iniciar-sesion.component.scss'
})
export class IniciarSesionComponent implements OnInit{
  userForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.userForm = this.fb.group({
      nick: ['', [Validators.required]],
      pass: ['', [Validators.required]],
      repeatpass: ['', Validators.required]
     });
  }

  ngOnInit(): void {
  }

  onSubmit(): void {
    if (this.userForm.invalid) return;

    const formData = this.userForm.value;
  }

  // Getters para los campos
  get nick() { return this.userForm.get('email'); }
  get pass() { return this.userForm.get('pass'); }
  get repeatpass() { return this.userForm.get('repeatpass'); }


}
