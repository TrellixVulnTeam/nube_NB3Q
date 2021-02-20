import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailOwnerComponent } from './detail-owner.component';

describe('DetailOwnerComponent', () => {
  let component: DetailOwnerComponent;
  let fixture: ComponentFixture<DetailOwnerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DetailOwnerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailOwnerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
