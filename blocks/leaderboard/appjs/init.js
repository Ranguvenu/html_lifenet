let that = this;
class AddonBlockLeaderboardHandler {
    name = 'AddonBlockLeaderboardHandler';
    blockName = 'block_leaderboard';


    async isEnabled() {
        return true;
    }

    getDisplayData() {
        return {
            title: 'plugin.block_leaderboard.pluginname',
            class: 'addon-block-leaderboard',
            component: that.CoreBlockPreRenderedComponent,
        };
    }
}

// that.CoreBlockDelegate.registerHandler(new AddonBlockLeaderboardHandler());
