// Dữ liệu tĩnh cho chức năng và quyền
let functions = [
    { id: 1, name: 'Xem nhân viên' },
    { id: 2, name: 'Thêm nhân viên' },
    { id: 3, name: 'Sửa nhân viên' },
    { id: 4, name: 'Khóa nhân viên' },
    { id: 5, name: 'Xem sản phẩm' },
    { id: 6, name: 'Thêm sản phẩm' },
    { id: 7, name: 'Sửa sản phẩm' },
    { id: 8, name: 'Xóa sản phẩm' },
    { id: 9, name: 'Xem tài khoản' },
    { id: 10, name: 'Thêm tài khoản' },
    { id: 11, name: 'Sửa tài khoản' },
    { id: 12, name: 'Khóa tài khoản' }
];

let roles = {
    'Admin': {
        1: true, 2: true, 3: true, 4: true,
        5: true, 6: true, 7: true, 8: true,
        9: true, 10: true, 11: true, 12: true
    },
    'Editor': {
        1: true, 2: true, 3: true, 4: false,
        5: true, 6: true, 7: true, 8: false,
        9: true, 10: false, 11: false, 12: false
    },
    'Viewer': {
        1: true, 2: false, 3: false, 4: false,
        5: true, 6: false, 7: false, 8: false,
        9: true, 10: false, 11: false, 12: false
    }
};

// Khóa/Mở khóa người dùng
function toggleLock(userId) {
    const row = document.querySelector(`#userTable tr[data-id="${userId}"]`);
    const statusSpan = row.querySelector('.status');
    const lockBtn = row.querySelector('.lock-btn');
    const isLocked = statusSpan.textContent === 'Khóa';

    if (isLocked) {
        statusSpan.textContent = 'Mở';
        statusSpan.className = 'text-success fw-bold status';
        lockBtn.textContent = 'Khóa';
        lockBtn.className = 'btn btn-sm btn-danger lock-btn';
    } else {
        statusSpan.textContent = 'Khóa';
        statusSpan.className = 'text-danger fw-bold status';
        lockBtn.textContent = 'Mở khóa';
        lockBtn.className = 'btn btn-sm btn-success lock-btn';
    }
}

// Hiển thị modal thêm tài khoản
function showAddAccountModal() {
    document.getElementById('newUsername').value = '';
    document.getElementById('newEmail').value = '';
    document.getElementById('newRole').value = 'Admin';
    new bootstrap.Modal(document.getElementById('addAccountModal')).show();
}

// Thêm tài khoản (chỉ hiển thị alert vì dữ liệu trong bảng là cứng)
function addAccount() {
    const username = document.getElementById('newUsername').value;
    const email = document.getElementById('newEmail').value;
    const role = document.getElementById('newRole').value;
    alert(`Đã thêm tài khoản: ${username} | ${email} | ${role}`);
    bootstrap.Modal.getInstance(document.getElementById('addAccountModal')).hide();
}

// Hiển thị modal phân quyền
function editPermissions(userId) {
    document.getElementById('permissionUserId').value = userId;
    document.getElementById('roleName').value = userId === 1 ? 'Admin' : userId === 2 ? 'Editor' : 'Viewer';
    const tbody = document.getElementById('permissionsTable');
    tbody.innerHTML = '';
    functions.forEach(func => {
        const checked = roles[userId === 1 ? 'Admin' : userId === 2 ? 'Editor' : 'Viewer']?.[func.id] ? 'checked' : '';
        const row = `
            <tr>
                <td>${func.name}</td>
                <td><input type="checkbox" name="functions[${func.id}]" value="1" ${checked}></td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    new bootstrap.Modal(document.getElementById('permissionsModal')).show();
}

// Lưu phân quyền
function savePermissions() {
    const userId = parseInt(document.getElementById('permissionUserId').value);
    const roleName = document.getElementById('roleName').value.trim();
    const form = document.getElementById('permissionsForm');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    const newPermissions = {};
    checkboxes.forEach(cb => {
        const funcId = parseInt(cb.name.match(/\d+/)[0]);
        newPermissions[funcId] = cb.checked;
    });
    roles[roleName] = newPermissions;
    alert(`Đã cập nhật phân quyền cho ${roleName}`);
    bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
}

// Hiển thị modal chỉnh sửa nhóm quyền
function showRoleModal() {
    document.getElementById('editRoleName').value = '';
    const tbody = document.getElementById('rolePermissionsTable');
    tbody.innerHTML = '';
    const groupedFunctions = {
        'nhân viên': { view: 1, add: 2, edit: 3, delete: 4 },
        'sản phẩm': { view: 5, add: 6, edit: 7, delete: 8 },
        'tài khoản': { view: 9, add: 10, edit: 11, delete: 12 }
    };
    for (let name in groupedFunctions) {
        const actions = groupedFunctions[name];
        const row = `
            <tr>
                <td>${name}</td>
                <td>${actions.view ? `<input type="checkbox" name="functions[${actions.view}]" value="1">` : ''}</td>
                <td>${actions.add ? `<input type="checkbox" name="functions[${actions.add}]" value="1">` : ''}</td>
                <td>${actions.edit ? `<input type="checkbox" name="functions[${actions.edit}]" value="1">` : ''}</td>
                <td>${actions.delete ? `<input type="checkbox" name="functions[${actions.delete}]" value="1">` : ''}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    };
    new bootstrap.Modal(document.getElementById('roleModal')).show();
}

// Lưu nhóm quyền
function saveRole() {
    const roleName = document.getElementById('editRoleName').value.trim();
    const form = document.getElementById('roleForm');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    const newPermissions = {};
    checkboxes.forEach(cb => {
        const funcId = parseInt(cb.name.match(/\d+/)[0]);
        newPermissions[funcId] = cb.checked;
    });
    roles[roleName] = newPermissions;
    alert(`Đã lưu nhóm quyền: ${roleName}`);
    bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
}