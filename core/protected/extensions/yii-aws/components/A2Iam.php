<?php
/**
 * A2IAm class
 *
 * A wrapper class for the Client to interact with AWS Identity and Access Management
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with AWS Identity and Access Management
 *
 * @method Model addRoleToInstanceProfile(array $args = array()) {@command Iam AddRoleToInstanceProfile}
 * @method Model addUserToGroup(array $args = array()) {@command Iam AddUserToGroup}
 * @method Model changePassword(array $args = array()) {@command Iam ChangePassword}
 * @method Model createAccessKey(array $args = array()) {@command Iam CreateAccessKey}
 * @method Model createAccountAlias(array $args = array()) {@command Iam CreateAccountAlias}
 * @method Model createGroup(array $args = array()) {@command Iam CreateGroup}
 * @method Model createInstanceProfile(array $args = array()) {@command Iam CreateInstanceProfile}
 * @method Model createLoginProfile(array $args = array()) {@command Iam CreateLoginProfile}
 * @method Model createRole(array $args = array()) {@command Iam CreateRole}
 * @method Model createUser(array $args = array()) {@command Iam CreateUser}
 * @method Model createVirtualMFADevice(array $args = array()) {@command Iam CreateVirtualMFADevice}
 * @method Model deactivateMFADevice(array $args = array()) {@command Iam DeactivateMFADevice}
 * @method Model deleteAccessKey(array $args = array()) {@command Iam DeleteAccessKey}
 * @method Model deleteAccountAlias(array $args = array()) {@command Iam DeleteAccountAlias}
 * @method Model deleteAccountPasswordPolicy(array $args = array()) {@command Iam DeleteAccountPasswordPolicy}
 * @method Model deleteGroup(array $args = array()) {@command Iam DeleteGroup}
 * @method Model deleteGroupPolicy(array $args = array()) {@command Iam DeleteGroupPolicy}
 * @method Model deleteInstanceProfile(array $args = array()) {@command Iam DeleteInstanceProfile}
 * @method Model deleteLoginProfile(array $args = array()) {@command Iam DeleteLoginProfile}
 * @method Model deleteRole(array $args = array()) {@command Iam DeleteRole}
 * @method Model deleteRolePolicy(array $args = array()) {@command Iam DeleteRolePolicy}
 * @method Model deleteServerCertificate(array $args = array()) {@command Iam DeleteServerCertificate}
 * @method Model deleteSigningCertificate(array $args = array()) {@command Iam DeleteSigningCertificate}
 * @method Model deleteUser(array $args = array()) {@command Iam DeleteUser}
 * @method Model deleteUserPolicy(array $args = array()) {@command Iam DeleteUserPolicy}
 * @method Model deleteVirtualMFADevice(array $args = array()) {@command Iam DeleteVirtualMFADevice}
 * @method Model enableMFADevice(array $args = array()) {@command Iam EnableMFADevice}
 * @method Model getAccountPasswordPolicy(array $args = array()) {@command Iam GetAccountPasswordPolicy}
 * @method Model getAccountSummary(array $args = array()) {@command Iam GetAccountSummary}
 * @method Model getGroup(array $args = array()) {@command Iam GetGroup}
 * @method Model getGroupPolicy(array $args = array()) {@command Iam GetGroupPolicy}
 * @method Model getInstanceProfile(array $args = array()) {@command Iam GetInstanceProfile}
 * @method Model getLoginProfile(array $args = array()) {@command Iam GetLoginProfile}
 * @method Model getRole(array $args = array()) {@command Iam GetRole}
 * @method Model getRolePolicy(array $args = array()) {@command Iam GetRolePolicy}
 * @method Model getServerCertificate(array $args = array()) {@command Iam GetServerCertificate}
 * @method Model getUser(array $args = array()) {@command Iam GetUser}
 * @method Model getUserPolicy(array $args = array()) {@command Iam GetUserPolicy}
 * @method Model listAccessKeys(array $args = array()) {@command Iam ListAccessKeys}
 * @method Model listAccountAliases(array $args = array()) {@command Iam ListAccountAliases}
 * @method Model listGroupPolicies(array $args = array()) {@command Iam ListGroupPolicies}
 * @method Model listGroups(array $args = array()) {@command Iam ListGroups}
 * @method Model listGroupsForUser(array $args = array()) {@command Iam ListGroupsForUser}
 * @method Model listInstanceProfiles(array $args = array()) {@command Iam ListInstanceProfiles}
 * @method Model listInstanceProfilesForRole(array $args = array()) {@command Iam ListInstanceProfilesForRole}
 * @method Model listMFADevices(array $args = array()) {@command Iam ListMFADevices}
 * @method Model listRolePolicies(array $args = array()) {@command Iam ListRolePolicies}
 * @method Model listRoles(array $args = array()) {@command Iam ListRoles}
 * @method Model listServerCertificates(array $args = array()) {@command Iam ListServerCertificates}
 * @method Model listSigningCertificates(array $args = array()) {@command Iam ListSigningCertificates}
 * @method Model listUserPolicies(array $args = array()) {@command Iam ListUserPolicies}
 * @method Model listUsers(array $args = array()) {@command Iam ListUsers}
 * @method Model listVirtualMFADevices(array $args = array()) {@command Iam ListVirtualMFADevices}
 * @method Model putGroupPolicy(array $args = array()) {@command Iam PutGroupPolicy}
 * @method Model putRolePolicy(array $args = array()) {@command Iam PutRolePolicy}
 * @method Model putUserPolicy(array $args = array()) {@command Iam PutUserPolicy}
 * @method Model removeRoleFromInstanceProfile(array $args = array()) {@command Iam RemoveRoleFromInstanceProfile}
 * @method Model removeUserFromGroup(array $args = array()) {@command Iam RemoveUserFromGroup}
 * @method Model resyncMFADevice(array $args = array()) {@command Iam ResyncMFADevice}
 * @method Model updateAccessKey(array $args = array()) {@command Iam UpdateAccessKey}
 * @method Model updateAccountPasswordPolicy(array $args = array()) {@command Iam UpdateAccountPasswordPolicy}
 * @method Model updateAssumeRolePolicy(array $args = array()) {@command Iam UpdateAssumeRolePolicy}
 * @method Model updateGroup(array $args = array()) {@command Iam UpdateGroup}
 * @method Model updateLoginProfile(array $args = array()) {@command Iam UpdateLoginProfile}
 * @method Model updateServerCertificate(array $args = array()) {@command Iam UpdateServerCertificate}
 * @method Model updateSigningCertificate(array $args = array()) {@command Iam UpdateSigningCertificate}
 * @method Model updateUser(array $args = array()) {@command Iam UpdateUser}
 * @method Model uploadServerCertificate(array $args = array()) {@command Iam UploadServerCertificate}
 * @method Model uploadSigningCertificate(array $args = array()) {@command Iam UploadSigningCertificate}
 */
class A2IAm extends A2S3
{
	/**
	 * @return Aws\Iam\IamClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_IAM);
		}
		return $this->_client;
	}
}