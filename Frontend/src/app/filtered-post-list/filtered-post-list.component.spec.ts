import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FilteredPostListComponent } from './filtered-post-list.component';

describe('FilteredPostListComponent', () => {
  let component: FilteredPostListComponent;
  let fixture: ComponentFixture<FilteredPostListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FilteredPostListComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FilteredPostListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
