# Git

## Git Config (For Aliases)

```txt
a = add .
acm = !git add . && git commit -m
rt = restore .
f = fetch
co = checkout
cd = checkout develop
cb = checkout -b
fco = !git fetch && git checkout
br = branch
ci = commit
cm = commit -m
ca = commit --amend
cp = cherry-pick
st = status
sa = stash apply
lone = log --oneline
last = log -1 HEAD
reset = reset --hard HEAD~1
unstage = reset --soft HEAD^
current = rev-parse --abbrev-ref HEAD
plo = pull origin
pld = pull origin develop
plm = pull origin main
plb = pull origin feature/upgrade_bootstrap4_install
plc = !CURRENT=$(git current) && git pull origin $CURRENT
psc = !CURRENT=$(git current) && git push origin $CURRENT
pso = push origin
dev = !git checkout develop && git pull origin develop
main = !git checkout main && git pull origin main
brd = !sh -c 'git branch -l $1/* | xargs git branch -D' -
brdall = !sh -c 'git branch -l | xargs git branch -D' -
```

## Get current branch

```sh
git rev-parse --abbrev-ref HEAD
```

## Get all local branches with prefix

```sh
git branch -l <prefix>/*

# Can delete found branches
git branch -l <prefix>/* | xargs git branch -D
```

## Get history of the repository

```sh
git log

# gives useful information about refs names
git log --decorate <--online>

# only short hash and commit message
git log --oneline

# with visual representation
git log --online --graph

# show all branches
git log --online --graph --all --decorate
```

Read more about git log here: <https://www.atlassian.com/git/tutorials/git-log>

## Check local changes

```sh
git diff
```

## Check URL of the cloned repository

```sh
grep -A 'remote "origin"' .git/config
```

## Recover repository state

```sh
git reset

# default flag (staged to unstaged)
# keeps items altered in current working tree
git reset --mixed

# reset working tree to last commit
# lose changes of the items
git reset --hard
```

## Branch out from specific commit

```sh
# checkout commit to detached head
git checkout <commit hash>

# from detached head to new branch
git checkout -b <new branch_name>
```

## Stashing changes

```sh
# commit the local change to refs/stash branch
# and merge as a chield of HEAD on new refs/stash branch
git stash

# retrieve list
git stash list

# reapply changes on the same codebase
git stash pop

# get individual stash information
git stash show --patch <ID>
#git stash show --patch stash@{1}

# applying a specific stash
git stash apply <ID>
#git stash apply stash@{1}

# remove specific stash
git stash drop <ID>
#git stash drop stash@{1}
```

## Notes

- A `branch` is a pointer to the end of a line of changes.
- A `tag` is a pointer to a single change.
- `HEAD` is where your Git repository is right now.
- `Detached HEAD` means you are at a commit that has no reference (branch or tag) associated with it.
- `git stash apply` remains in the list but `git stash pop` will remove the stash item
