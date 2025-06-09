import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CambiarAvatarComponent } from './cambiar-avatar.component';

describe('CambiarAvatarComponent', () => {
  let component: CambiarAvatarComponent;
  let fixture: ComponentFixture<CambiarAvatarComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CambiarAvatarComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CambiarAvatarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
