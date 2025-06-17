import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CambiarPrivacidadComponent } from './cambiar-privacidad.component';

describe('CambiarPrivacidadComponent', () => {
  let component: CambiarPrivacidadComponent;
  let fixture: ComponentFixture<CambiarPrivacidadComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CambiarPrivacidadComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CambiarPrivacidadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
