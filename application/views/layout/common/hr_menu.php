<div class="card">
  <div class="card-header">
    <h3 class="card-title">HR Modules</h3>
    <div class="card-tools">
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button> -->
    </div>
  </div>
  <div class="card-body p-0">
    <ul class="nav nav-pills flex-column">

    <li class="nav-item <?= ($this->uri->segment(1) == 'department') ? 'active' : '' ?>">
        <a href="<?= base_url('department') ?>" class="nav-link">
          <i class="far fa-building mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_department') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'position') ? 'active' : '' ?>">
        <a href="<?= base_url('position') ?>" class="nav-link">
          <i class="fas fa-map-marked mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_position') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'weekend') ? 'active' : '' ?>">
        <a href="<?= base_url('weekend') ?>" class="nav-link">
          <i class="fas fa-hat-wizard mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_weekends') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'holiday') ? 'active' : '' ?>">
        <a href="<?= base_url('holiday') ?>" class="nav-link">
          <i class="fas fa-holly-berry mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_holidays') ?></span>
        </a>
      </li>

      <li class="nav-item <?= ($this->uri->segment(1) == 'employee') ? 'active' : '' ?>">
        <a href="<?= base_url('employee') ?>" class="nav-link">
          <i class="fas fa-user mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_employee') ?></span>
        </a>
      </li>
      
      <li class="nav-item <?= ($this->uri->segment(1) == 'attendance') ? 'active' : '' ?>">
        <a href="<?= base_url('attendance') ?>" class="nav-link">
          <i class="fas fa-filter mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_attendance') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'leave') ? 'active' : '' ?>">
        <a href="<?= base_url('leave') ?>" class="nav-link">
          <i class="fab fa-ravelry mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_leave') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'advance_salary') ? 'active' : '' ?>">
        <a href="<?= base_url('advance_salary') ?>" class="nav-link">
          <i class="fas fa-comment-dollar mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_advance_salary') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'bonus') ? 'active' : '' ?>">
        <a href="<?= base_url('bonus') ?>" class="nav-link">
          <i class="fas fa-hand-holding-heart mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_bonus') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'deduction') ? 'active' : '' ?>">
        <a href="<?= base_url('deduction') ?>" class="nav-link">
          <i class="fas fa-shapes mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_deduction') ?></span>
        </a>
      </li>
      
      
     
      <li class="nav-item <?= ($this->uri->segment(1) == 'tax_deduction') ? 'active' : '' ?>">
        <a href="<?= base_url('tax_deduction') ?>" class="nav-link">
          <i class="fab fa-ravelry mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_tax_deduction') ?></span>
        </a>
      </li>
      <li class="nav-item <?= ($this->uri->segment(1) == 'payroll') ? 'active' : '' ?>">
        <a href="<?= base_url('payroll') ?>" class="nav-link">
          <i class="fab fa-ravelry mr-2"></i>
          <span class="nav-text"><?= $this->lang->line('header_payrolls') ?></span>
        </a>
      </li>
      
    </ul>
  </div>
  <!-- /.card-body -->
</div>

<style>
  .nav-item .nav-link {
    display: flex;
    align-items: center;
  }
  .nav-item .nav-link .nav-text {
    flex: 1;
  }
  .nav-item .nav-link i {
    min-width: 20px; /* Adjust the width to make sure all icons take up the same space */
    text-align: center;
  }
  .nav-item.active .nav-link {
    font-weight: bold;
    color: black;
    background-color: #f0f0f0;
  }
  .card {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
  }
</style>
