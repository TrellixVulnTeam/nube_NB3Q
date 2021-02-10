import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormOwnersComponent } from './form-owners.component';

describe('FormOwnersComponent', () => {
  let component: FormOwnersComponent;
  let fixture: ComponentFixture<FormOwnersComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormOwnersComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(FormOwnersComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
