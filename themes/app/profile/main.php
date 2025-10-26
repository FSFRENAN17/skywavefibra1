<?php $this->layout("_theme"); ?>

<!--begin::Profile Card-->
<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0">Meu Perfil</h3>
            <span class="text-muted">Gerencie suas informações pessoais, contatos e endereço.</span>
        </div>
        <button type="submit" form="profileForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar Alterações
        </button>
    </div>

    <form id="profileForm" class="form" enctype="multipart/form-data" method="post"
        action="<?= url("/app/profile-save"); ?>">
        <div class="card-body p-9">

            <!-- Foto -->
            <div class="row mb-10 align-items-center">
                <label class="col-lg-3 col-form-label fw-semibold">Foto de Perfil</label>
                <div class="col-lg-9">
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                        style="background-image: url('assets/media/svg/avatars/blank.svg')">
                        <div class="image-input-wrapper w-125px h-125px"
                            style="background-image: url('<?= $user->photo(); ?>')">
                        </div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Alterar foto">
                            <i class="ki-outline ki-pencil fs-7"></i>
                            <input type="file" name="photo" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="photo_remove" />
                        </label>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remover">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                    </div>
                    <div class="form-text">Formatos aceitos: JPG, PNG até 2MB</div>
                </div>
            </div>

            <!-- Dados pessoais -->
            <h4 class="fw-bold border-bottom pb-2 mb-4">Dados Pessoais</h4>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Nome Completo</label>
                <div class="col-lg-9">
                    <input type="text" name="full_name" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->full_name; ?>" required>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Documento</label>
                <div class="col-lg-4">
                    <input type="text" name="document" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->document; ?>" required>
                </div>

                <label class="col-lg-2 col-form-label fw-semibold text-end">Tipo</label>
                <div class="col-lg-3">
                    <select name="person_type" class="form-select form-select-solid">
                        <option value="individual" <?= $user->person()->person_type === 'individual' ? 'selected' : '' ?>>
                            Pessoa Física
                        </option>
                        <option value="company" <?= $user->person()->person_type === 'company' ? 'selected' : '' ?>>
                            Pessoa Jurídica
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Nascimento</label>
                <div class="col-lg-4">
                    <input type="date" name="birth_date" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->birth_date; ?>">
                </div>

                <label class="col-lg-2 col-form-label fw-semibold text-end">E-mail</label>
                <div class="col-lg-3">
                    <input type="email" name="email" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->email; ?>" required>
                </div>
            </div>

            <!-- Contatos -->
            <h4 class="fw-bold border-bottom pb-2 mt-9 mb-4">Contatos</h4>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Telefone</label>
                <div class="col-lg-4">
                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid"
                        value="<?= contact_value($user->person()->id, 'phone'); ?>">
                </div>

                <label class="col-lg-2 col-form-label fw-semibold text-end">Celular / WhatsApp</label>
                <div class="col-lg-3">
                    <input type="text" name="whatsapp" class="form-control form-control-lg form-control-solid"
                        value="<?= contact_value($user->person()->id, 'whatsapp'); ?>">
                </div>
            </div>

            <!-- Endereço -->
            <h4 class="fw-bold border-bottom pb-2 mt-9 mb-4">Endereço</h4>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Rua</label>
                <div class="col-lg-6">
                    <input type="text" name="street" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->street ?? ''; ?>">
                </div>

                <label class="col-lg-1 col-form-label fw-semibold text-end">Nº</label>
                <div class="col-lg-2">
                    <input type="text" name="number" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->number ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Bairro</label>
                <div class="col-lg-3">
                    <input type="text" name="district" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->district ?? ''; ?>">
                </div>

                <label class="col-lg-2 col-form-label fw-semibold text-end">Cidade</label>
                <div class="col-lg-2">
                    <input type="text" name="city" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->city ?? ''; ?>">
                </div>

                <label class="col-lg-1 col-form-label fw-semibold text-end">UF</label>
                <div class="col-lg-1">
                    <input type="text" name="state" maxlength="2" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->state ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">CEP</label>
                <div class="col-lg-3">
                    <input type="text" name="zipcode" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->zipcode ?? ''; ?>">
                </div>

                <label class="col-lg-2 col-form-label fw-semibold text-end">Complemento</label>
                <div class="col-lg-4">
                    <input type="text" name="complement" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person()->address()->complement ?? ''; ?>">
                </div>
            </div>

        </div>
    </form>
</div>
<!--end::Profile Card-->