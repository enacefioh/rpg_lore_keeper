<?php
use PHPUnit\Framework\TestCase;

class UserPermissionsTest extends TestCase
{
    private $admin_id;
    private $user_id;
    private $slug = "testgame";

    protected function setUp(): void
    {
        global $db, $DATABASE_PATH;
        // La conexión global $db es de ena_login.php
        // En el bootstrap.php de tests ya deberíamos haber inicializado esto.
        // Pero para estar seguros, si no está inicializada o no es memoria, la forzamos.
        
        // Limpiamos las tablas (o las creamos si es la primera vez en este hilo de test)
        // ena_login maneja sus propias tablas.
    }

    public function testUserRoleLifecycle()
    {
        global $db;
        $slug = $this->slug;
        $admin_perm = $slug . "_admin";
        $player_perm = $slug . "_jugador";

        // 1. Crear usuario de prueba
        $res = create_user("testuser_" . time(), "test@example.com", "password123");
        $this->assertTrue($res['success']);
        $u_id = $res['id'];

        // 2. Asegurar que los permisos existen
        $admin_p_id = get_permission_id($admin_perm);
        if ($admin_p_id == -1) {
            create_perm($admin_perm, $slug, "general", "Desc");
            $admin_p_id = get_permission_id($admin_perm);
        }
        $player_p_id = get_permission_id($player_perm);
        if ($player_p_id == -1) {
            create_perm($player_perm, $slug, "general", "Desc");
            $player_p_id = get_permission_id($player_perm);
        }

        // 3. Añadir como jugador
        $resAdd = add_perm_to_user($player_p_id, $u_id);
        $this->assertTrue($resAdd['success']);

        // Verificar
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_permissions WHERE user_id = ? AND permission_id = ?");
        $stmt->execute([$u_id, $player_p_id]);
        $this->assertEquals(1, $stmt->fetchColumn());

        // 4. Añadir como administrador
        $resAddAdmin = add_perm_to_user($admin_p_id, $u_id);
        $this->assertTrue($resAddAdmin['success']);

        // Verificar total 2 permisos
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_permissions WHERE user_id = ?");
        $stmt->execute([$u_id]);
        $this->assertEquals(2, $stmt->fetchColumn());

        // 5. Eliminar un permiso
        $resRem = remove_perm_from_user($player_p_id, $u_id);
        $this->assertTrue($resRem['success']);

        // Verificar queda 1
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_permissions WHERE user_id = ?");
        $stmt->execute([$u_id]);
        $this->assertEquals(1, $stmt->fetchColumn());
    }
}
