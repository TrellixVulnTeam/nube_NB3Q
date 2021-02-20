import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PersonasAddComponent } from './personas-add.component';

describe('PersonasAddComponent', () => {
  let component: PersonasAddComponent;
  let fixture: ComponentFixture<PersonasAddComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PersonasAddComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PersonasAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
