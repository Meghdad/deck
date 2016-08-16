<?php

namespace OCA\Deck\Controller;

use OCA\Deck\Service\BoardService;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IUserManager;
use OCP\IGroupManager;

class BoardController extends Controller {
    private $userId;
    private $boardService;
    protected $userManager;
    protected $groupManager;
    public function __construct($appName,
                                IRequest $request,
                                IUserManager $userManager,
                                IGroupManager $groupManager,
                                BoardService $cardService,
                                $userId) {
        parent::__construct($appName, $request);
        $this->userId = $userId;
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
        $this->boardService = $cardService;
        $this->userInfo = $this->getBoardPrequisites();
    }

    private function getBoardPrequisites() {
        $groups = $this->groupManager->getUserGroupIds($this->userManager->get($this->userId));
        return [
            'user' => $this->userId,
            'groups' => $groups
        ];
    }

    /**
     * @NoAdminRequired
     */
    public function index() {

        return $this->boardService->findAll($this->userInfo);
    }

    /**
     * @NoAdminRequired
     */
    public function read($boardId) {
        // FIXME: Remove as this is just for testing if loading animation works out nicely
        //usleep(2000);
        return $this->boardService->find($this->userId, $boardId);
    }

    /**
     * @NoAdminRequired
     */
    public function create($title, $color) {
        return $this->boardService->create($title, $this->userId, $color);
    }

    /**
     * @NoAdminRequired
     */
    public function update($id, $title, $color) {
        return $this->boardService->update($id, $title, $this->userId, $color);
    }

    /**
     * @NoAdminRequired
     */
    public function delete($boardId) {
        return $this->boardService->delete($this->userId, $boardId);
    }

    public function labels($boardId) {
        return $this->boardService->labels($this->boardId);
    }

    public function addAcl($boardId, $type, $participant, $write, $invite, $manage) {
        return $this->boardService->addAcl($boardId, $type, $participant, $write, $invite, $manage);
    }
    public function updateAcl($id, $permissionWrite, $permissionInvite, $permissionManage) {
        return $this->boardService->updateAcl($id, $permissionWrite, $permissionInvite, $permissionManage);
    }
    public function deleteAcl($id) {
        return $this->boardService->deleteAcl($id);
    }

}